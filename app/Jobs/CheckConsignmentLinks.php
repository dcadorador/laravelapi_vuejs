<?php

namespace App\Jobs;

use App\Models\IntegrationRecords;
use App\Models\IntegrationSyncs;
use App\Process\Process;
use App\Repositories\IntegrationRecordsRepository;
use App\Traits\LoggerTrait;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckConsignmentLinks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LoggerTrait;

    /**
     * @var IntegrationRecords
     */
    protected $integration_record;

    /**
     * @var
     */
    protected $platform;

    /**
     * @var
     */
    protected $shipped_with;

    /**
     * @var IntegrationRecordsRepository
     */
    private $integration_record_repo;

    /**
     * Create a new job instance.
     *
     * @param IntegrationRecords $record
     * @param $shipped_with
     */
    public function __construct(IntegrationRecords $record, $shipped_with)
    {
        //
        $this->integration_record_repo = new IntegrationRecordsRepository(app());
        $this->integration_record = $record;
        $this->shipped_with = $shipped_with;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // initialize the platform data
        $integration = $this->integration_record->integration;
        $integration_sync = $this->integration_record->integrationSyncs;
        $params = $integration->getIntegrationMeta();
        $process = new Process($integration);
        $this->platform = $integration->integrationType->getPlatformClass();
        $this->platform->setIntegration($integration);
        $this->platform->init($params);

        // get the integration details from the platform
        try {
            $fulfillment = $this->platform->findByTransaction($this->shipped_with['value']);

            if (is_null($fulfillment)) {
                $this->infoLog(
                    'Processing Consignment Link',
                    'Parent Fulfillment NOT FOUND for integration record: ' . $this->integration_record->id
                );
                $this->integration_record->record_status = IntegrationRecords::RECORD_STATUS_SKIPPED;
                $this->integration_record->save();
                return null;
            }
        } catch (\Exception $e) {
            $this->errorLog(
                'Error Processing Consignment Link',
                'Consignment Link Error with integration record: ' . $this->integration_record->id . ' - error: ' . json_encode($e->getMessage())
            );
            $this->integration_record->record_status = IntegrationRecords::RECORD_STATUS_ERROR;
            $this->integration_record->save();
            return null;
        }

        // check if the parent is locally found
        $parent_integ_record = $this->integration_record_repo->findWhere(['source_id' => $fulfillment->internalId])->first();

        if (!$parent_integ_record) {
            $this->infoLog(
                'Processing Consignment Link',
                'Parent Fulfillment NOT FOUND in LOCAL DB for integration record: ' . $this->integration_record->id
            );
            // skip this
            $this->integration_record->record_status = IntegrationRecords::RECORD_STATUS_SKIPPED;
            $this->integration_record->save();
            return null;
        }

        // check the parent status
        switch ($parent_integ_record->record_status) {
            case IntegrationRecords::RECORD_STATUS_SKIPPED:
                $this->integration_record->record_status = IntegrationRecords::RECORD_STATUS_SKIPPED;
                $this->integration_record->save();
                break;
            case IntegrationRecords::RECORD_STATUS_ERROR:
                $this->integration_record->record_status = IntegrationRecords::RECORD_STATUS_ERROR;
                $this->integration_record->save();
                break;
            // if the parent is still pending machship, add 2 minutes before running the child integration
            case IntegrationRecords::RECORD_STATUS_PENDING_MACHSHIP:
                $process_after = Carbon::parse($parent_integ_record->process_after)->addMinutes(2)->toDateTimeString();
                $this->integration_record->process_after = $process_after;
                $this->integration_record->save();
                break;
            case IntegrationRecords::RECORD_STATUS_PROCESSED:
                $this->integration_record->machship_id = $parent_integ_record->machship_id;
                $this->integration_record->machship_consignment_status = $parent_integ_record->machship_consignment_status;
                $this->integration_record->record_status = IntegrationRecords::RECORD_STATUS_PROCESSED;
                $this->integration_record->save();

                // send update to source integration
                $status = new \stdClass();
                $status->name = $this->integration_record->machship_consignment_status;
                $process->updateSourceIntegrationData($this->integration_record, $status);
        }

        // check if there is still pending integration records, from the current sync
        $integration_records_count = $integration_sync->integrationRecords()
            ->where('record_status', IntegrationRecords::RECORD_STATUS_PENDING_MACHSHIP)
            ->count();

        // if the count is 0, then the sync should be update to status COMPLETED
        if ($integration_records_count == 0) {
            $integration_sync->sync_status = IntegrationSyncs::SYNC_STATUS_COMPLETED;
            $integration_sync->save();
        }

        return null;
    }
}
