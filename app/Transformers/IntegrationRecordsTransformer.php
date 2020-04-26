<?php

namespace App\Transformers;

use App\Models\IntegrationRecords;

class IntegrationRecordsTransformer extends BaseTransformer
{
    protected $type = 'integration_syncs';

    public function transform(IntegrationRecords $arr)
    {

        $created_at = '';
        $updated_at = '';

        if ($arr->created_at) {
            $date = date_create($arr->created_at, timezone_open('Australia/Melbourne'));
            $created_at = date_format($date, 'Y-m-d H:i:s');
        }
        if ($arr->updated_at) {
            $date = date_create($arr->updated_at, timezone_open('Australia/Melbourne'));
            $updated_at = date_format($date, 'Y-m-d H:i:s');
        }

        return [
           'id' => (int) $arr->id,
           'integration' => $arr->integration,
           'integration_id' => (int) $arr->integration_id,
           'integration_sync_id' => (int) $arr->integration_sync_id,
           'source_id' => (string) $arr->source_id,
           'machship_id' => (string) $arr->machship_id,
           'machship_consignment_status' => (string) $arr->machship_consignment_status,
           'record_status' => (string) $arr->record_status,
           'source_data' => $arr->source_data,
           'machship_payload' => $arr->machship_payload,
           'debug_logs' => $arr->debugLogs,
           'sync_logs' => $arr->syncLogs,
           'created_at' => (string) $created_at,
           'updated_at' => (string) $updated_at
        ];
    }
}
