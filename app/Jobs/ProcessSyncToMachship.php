<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\IntegrationRecords;
use Exception;
use App\Libraries\Machship\Machship;
use App\Models\IntegrationSyncs;
use App\Traits\ErrorNotificationTrait;
use App\Traits\LoggerTrait;

class ProcessSyncToMachship implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ErrorNotificationTrait, LoggerTrait;

    private $integrationSyncs;
    private $integration;
    private $platform;
    private $machship;

    public $tries = 5;
    public $timeout = 1280;
    public $retryAfter = 60;

    const TAG = '[SYNC_TO_MACHSHIP]';
    const STEP = 4;

    /**
     * Create a new job instance.
     *
     * @param IntegrationSyncs $integrationSyncs
     */
    public function __construct(IntegrationSyncs $integrationSyncs)
    {
        $this->integrationSyncs = $integrationSyncs;
        $this->integration = $this->integrationSyncs->integration;
        // get integration and set machship config
        $token = $this->integration->getMachshipTokenKey();

        // token must not be empty
        if (empty($token)) {
            $this->infoLog('NO MACHSHIP TOKEN');
        } else {
            $this->machship = new Machship($token);
            $this->platform = $this->integration->integrationType->getPlatformClass();
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->machship)) {
            return;
        }

        // get data from integration_records with PENDING_MACHSHIP status
        $integration_records = $this->integrationSyncs->integrationRecords()
            ->where('record_status', '=', IntegrationRecords::RECORD_STATUS_PENDING_MACHSHIP)
            ->get();

        $to_process_after = null;
        foreach ($integration_records as $record) {
            if ($record->process_after > Carbon::now()->toDateTimeString()) {
                $to_process_after = $record->process_after;
                continue;
            }
            $record->machship_consignment_status = IntegrationRecords::RECORD_STATUS_PROCESSING;
            $this->sendToMachship($record->machship_payload, $record);
        }

        // check if there are more records to process after
        if ($to_process_after) {
            $mins = Carbon::now()->diffInMinutes($to_process_after);
            ProcessSyncToMachship::dispatch($this->integrationSyncs);
        } else {
            // otherwise we need to set the sync status to completed
            $this->integrationSyncs->sync_status = IntegrationSyncs::SYNC_STATUS_COMPLETED;
            $this->integrationSyncs->save();
        }
    }

    private function sendToMachship($data, $integration_record)
    {
        try {
            $result = [];

            if ($integration_record->consignment_type == IntegrationRecords::RECORD_CONSIGNMENT_TYPE_PENDING) {
                $this->infoLog('Success! pending consignment', $data);
                $result = $this->machship->createPendingConsignment($data);
                $integration_record->machship_consignment_status = IntegrationRecords::RECORD_CONSIGNMENT_STATUS_PENDING;

            // otherwise create as full consignment
            } else {
                $this->infoLog('Success! manifest consignment', $data);
                $result = $this->machship->createConsignment($data);
                $integration_record->machship_consignment_status = IntegrationRecords::RECORD_CONSIGNMENT_STATUS_UNMANIFESTED;
            }

            $this->infoLog('[Test] result from consignment creation', $result);


            // we need to check result here before proceeding
            if (empty($result)) {
                throw new Exception("Machship result is empty!");
            }

            if (empty($result->object)) {
                throw new Exception(json_encode($result->errors));
            }


            // update record status via platform setup
            $record_status = $this->platform->getIntegrationRecordStatusAfterSyncToMachship();

            $integration_record->record_status = $record_status;
            $integration_record->machship_id = $result->object->id;
            $integration_record->save();

            return true;
        } catch (Exception $e) {
            // update record status
            $integration_record->record_status = IntegrationRecords::RECORD_STATUS_MACHSHIP_ERROR;
            $integration_record->save();

            $this->debugLog(self::STEP, 'ERROR', [
                'integration_id' => $this->integration->id,
                'integration_sync_id' => $this->integrationSyncs->id,
                'integration_record_id' => $integration_record->id,
                'data' => json_encode($e)
            ]);
            return false;
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $e)
    {
        // send email to client
        $this->sendErrorNotification($e);
    }
}
