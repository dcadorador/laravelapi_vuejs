<?php

namespace App\Process;

use App\Helpers\Helper;
use App\Libraries\Machship\Machship;
use App\Models\DebugLogs;
use App\Models\IntegrationRecords;
use App\Models\IntegrationSyncs;
use App\Repositories\DebugLogsRepository;
use App\Repositories\IntegrationRecordsRepository;
use App\Repositories\IntegrationSyncsRepository;
use App\Repositories\MachshipStatusMappingRepository;
use App\Traits\LoggerTrait;

class UpdateSourceIntegration
{
    use LoggerTrait;

    /**
     * @var IntegrationSyncsRepository
     */
    private $integration_sync_repository;

    /**
     * @var IntegrationRecordsRepository
     */
    private $integration_records_repository;

    /**
     * @var MachshipStatusMappingRepository
     */
    private $machship_status_mapping_repository;

    /**
     * @var DebugLogsRepository
     */
    private $debug_log_repository;

    public function __construct()
    {
        $this->infoLog(DebugLogs::STEP_WF_5, 'WF-5: Initialization');

        $this->integration_sync_repository = new IntegrationSyncsRepository(app());
        $this->integration_records_repository = new IntegrationRecordsRepository(app());
        $this->machship_status_mapping_repository = new MachshipStatusMappingRepository(app());
        $this->debug_log_repository = new DebugLogsRepository(app());

        $this->process();
    }

    public function process()
    {
        $this->infoLog(DebugLogs::STEP_WF_5, 'Started Machship Pending Update process at: ' . date('Y-m-d H:i:s'));

        // get pending update
        $syncs = $this->getSyncProcessed();

        // if no processed syncs, no need to get pending updates since sync are still processing.
        if (is_null($syncs)) {
            $this->infoLog(DebugLogs::STEP_WF_5, 'No Integration Sync Found.');
            return null;
        }

        $this->getPendingUpdateAndProcess($syncs);

        $this->infoLog(DebugLogs::STEP_WF_5, 'Ended Machship Pending Update process at: ' . date('Y-m-d H:i:s'));
    }

    /**
     * @return mixed|null
     */
    public function getSyncProcessed()
    {
        // get the pending update records based on the integration sync data
        // $syncs = $this->integration_sync_repository->findWhere([ 'sync_status' => IntegrationSyncs::SYNC_STATUS_COMPLETED ]);
        $syncs = $this->integration_sync_repository->findDueForSourceUpdate();

        if (empty($syncs)) {
            $this->infoLog(DebugLogs::STEP_WF_5, 'No Sync Processed Found.');
            return null;
        }

        return $syncs;
    }

    /**
     * Get Pending and Process
     * April 4, 2020 - Updated and added the Machship response for the integration record
     * @param $syncs
     * @return null
     * @throws \Exception
     */
    public function getPendingUpdateAndProcess($syncs)
    {

        foreach ($syncs as $sync) {
            // set integration
            $integration = $sync->integration()->first();

            // skip if there is no integration
            if (!$integration) {
                continue;
            }

            // get integration records that are pending update
            $integration_records = $this->integration_records_repository->findWhere([
                'integration_sync_id' => $sync->id,
                'record_status' => IntegrationRecords::RECORD_STATUS_PENDING_UPDATE
            ]);

            if ($integration_records->isEmpty()) {
                // $this->infoLog(DebugLogs::STEP_WF_5, 'No PENDING UPDATE records for Sync: ' . $sync->id);
                continue;
            }

            // initialize process class
            $process = new Process($integration);

            $pending_consignment = false;
            foreach ($integration_records as $record) {
                $machship_id = $record->machship_id;

                if (empty($machship_id)) {
                    $record->record_status = IntegrationRecords::RECORD_STATUS_SKIPPED;
                    $record->save();
                    continue;
                }

                // format the machship id and identification of pending or manifest
                if ($record->consignment_type == IntegrationRecords::RECORD_CONSIGNMENT_TYPE_PENDING) {
                    $pending_consignment = true;
                    $this->infoLog(DebugLogs::STEP_WF_5, 'Pending consignment: ' . $record->machship_id . ' for record ID: ' . $record->id);
                    $machship_id = $this->consignmentNumberFormatter($machship_id, true);
                } else {
                    $this->infoLog(DebugLogs::STEP_WF_5, 'Manifest consignment: ' . $record->machship_id . ' for record ID' . $sync->id);
                    $machship_id = $this->consignmentNumberFormatter($machship_id);
                }

                try {
                    if ($pending_consignment) {
                        $machship_response = $process->getMachshipConsignmentByPendingId($machship_id);
                    } else {
                        $machship_response = $process->getMachshipConsignmentById($machship_id);
                    }
                } catch (\Exception $e) {
                    $this->errorLog(DebugLogs::STEP_WF_5, 'Error Sending Status Api Call for Machship: ' . $machship_id);

                    $this->debug_log_repository->create([
                        'integration_id' => $integration->id,
                        'integration_sync_id' => $sync->id,
                        'integration_record_id' => $record->id,
                        'sync_step' => DebugLogs::STEP_WF_5,
                        'data' => 'ERROR API MACHSHIP CONSIGNMENT: ' .json_encode($e->getMessage()),
                    ]);

                    // set status record
                    $record->record_status = IntegrationRecords::RECORD_STATUS_MACHSHIP_ERROR;
                    $record->save();
                    continue;
                }

                // cannot get any machship consignment details
                if (is_null($machship_response->object)) {
                    $this->debug_log_repository->create([
                        'integration_id' => $integration->id,
                        'integration_sync_id' => $sync->id,
                        'integration_record_id' => $record->id,
                        'sync_step' => DebugLogs::STEP_WF_5,
                        'data' => 'NULL MACHSHIP CONSIGNMENT RESPONSE: ' . json_encode($machship_response),
                    ]);

                    $this->infoLog(DebugLogs::STEP_WF_5, 'No Machship Consignment returned: ' . json_encode($machship_response));
                    $record->record_status = IntegrationRecords::RECORD_STATUS_PENDING_UPDATE;
                    $record->save();
                    continue;
                }

                // get the status for consignment
                $machship_status = Helper::getTrackingStatus($machship_response);

                // update the record for the machship status
                $record->machship_consignment_status = $machship_status->name;
                $record->save();

                // check the machship status mapping table
                $machship_status_mapping = $this->machship_status_mapping_repository->findWhere([
                    'integration_id' => $integration->id,
                    'machship_status' => ucwords($machship_status->name)
                ]);

                if (!$machship_status_mapping->isEmpty()) {
                    // $this->infoLog(DebugLogs::STEP_WF_5, 'Machship Status Mapping Found: ' . json_encode($machship_status_mapping));
                    $machship_status_mapping = $machship_status_mapping[0];
                    $record->record_status = $machship_status_mapping->record_status;
                    $record->save();

                    if ($machship_status_mapping->update_source) {
                        $process->updateSourceIntegrationData($record, $machship_status, $machship_response->object); // added the machship response
                    }
                }
            }
        }

        return 'Done processing PENDING UPDATE integration records';
    }

    /**
     * @param $machship_id
     * @param bool $pending
     * @return string
     */
    private function consignmentNumberFormatter($machship_id, $pending = false)
    {
        return $pending ?
            ltrim(str_replace('PC', '', $machship_id), '0') :
            ltrim(str_replace('MS', '', $machship_id), '0') ;
    }
}
