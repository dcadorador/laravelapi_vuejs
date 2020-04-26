<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\IntegrationRecordsTransformer;
use App\Repositories\IntegrationRecordsRepository;
use App\Repositories\SyncLogsRepository;
use App\Repositories\DebugLogsRepository;
use App\Repositories\Criterias\IntegrationRecordsCriteria;
use App\Http\Controllers\ApiBaseController;
use App\Jobs\ProcessSyncToMachship;
use App\Models\IntegrationRecords;
use App\Process\Process;

class IntegrationRecordController extends ApiBaseController
{

    private $synclogs_repository;
    private $debuglogs_repository;

    public function __construct(
        IntegrationRecordsTransformer $integrationRecordsTransformer,
        IntegrationRecordsRepository $integrationRecordsRepository,
        SyncLogsRepository $syncLogsRepository,
        DebugLogsRepository $debugLogsRepository
    ) {
        $this->repository = $integrationRecordsRepository;
        $this->transformer = $integrationRecordsTransformer;
        $this->synclogs_repository = $syncLogsRepository;
        $this->debuglogs_repository = $debugLogsRepository;
        $this->validations = [];
    }


    public function index(Request $request)
    {

        // we need to check if what type of user trying to access here
        // if it is non admin, then display its own syncs record lists
        $user = \Auth::user();
        if ($user->hasRole('User')) {
            $accounts = $user->accounts;
            $ids = [];
            $integrations = [];
            foreach ($accounts as $account) {
                $integrations[] = $account->integrations;
            }

            $integrations = \Arr::collapse($integrations);

            foreach ($integrations as $integration) {
                if (in_array($integration->id, $ids)) {
                    continue;
                }
                $ids[] = $integration->id;
            }
            $request->merge(['integration_ids' => $ids]);
        }

        $this->repository->pushCriteria(new IntegrationRecordsCriteria($request));
        return parent::index($request);
    }


    /**
     * This function will re pull data from a specific record
     * 1. Execute WF-2 fetch source by id
     * 2. Execute WF-3 process record and data
     */
    public function repull(Request $request)
    {
        $record_id = $request->input('id');

        $record = $this->repository->find($record_id);
        $integration = $record->integration;
        $process = new Process($integration);

        // NOTE : Just when source id will not be enough
        $process->getPlatform()->setData($record->source_data);

        // WF-2 Fetch specific data by source id
        $data = $process->getPlatform()->findBySourceId($record->source_id);

        // after getting the data we need to add this to sync logs
        $this->synclogs_repository->addLogByRecord($record, 2, $data, '[REPULL] Execute repull');

        // check source_data
        if (empty($data)) {
            $this->debuglogs_repository->addLogByRecord($record, 2, '[REPULL] source data is empty');
            return response()->json([
                'status' => false,
                'message' => 'Fail to execute repull! Source data pulled is empty.'
            ]);
        }

        // WF-3 Execute
        $process->processRecordAndData($record, $data);
        $record->source_data = $data;
        $record->save();

        return response()->json(['status' => true]);
    }


    /**
     * This function will re process record and data without changes in source
     * 1. Execute WF-3 process record and data
     */
    public function reprocess(Request $request)
    {
        $record_id = $request->input('id');
        $record = $this->repository->find($record_id);
        $integration = $record->integration;
        $process = new Process($integration);
        $this->synclogs_repository->addLogByRecord($record, 3, null, '[REPROCESS] Execute reprocess');

        // WF-3 Execute
        $process->processRecordAndData($record, $record->source_data);

        return response()->json(['status' => true]);
    }

    /**
     * This function will re push record to execute WF-4
     */
    public function repush(Request $request)
    {
        $record_id = $request->input('id');
        $record = $this->repository->find($record_id);

        $this->synclogs_repository->addLogByRecord($record, 4, null, '[REPUSH] Execute repush');

        // validated if record can still be repush
        if ($record->record_status === IntegrationRecords::RECORD_STATUS_COMPLETED) {
            // record has been completed already
            $this->debuglogs_repository->addLogByRecord($record, 4, '[REPUSH] Failed to repush when record status is already completed.');
            return response()->json([
                'status' => false,
                'message' => 'Fail to execute repush! Record status has been completed already.'
            ]);
        }

        $integration_sync = $record->integrationSyncs;

        if (empty($integration_sync)) {
            // there is no integrion sync found
            $this->debuglogs_repository->addLogByRecord($record, 4, '[REPUSH] Failed to repush when record syncs does not exist');
            return response()->json([
                'status' => false,
                'message' => 'Fail to execute repush! there is no integration sync.'
            ]);
        }

        ProcessSyncToMachship::dispatch($integration_sync);

        return response()->json(['status' => true]);
    }
}
