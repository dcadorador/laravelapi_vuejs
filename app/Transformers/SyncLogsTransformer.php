<?php

namespace App\Transformers;

use App\Models\SyncLogs;

class SyncLogsTransformer extends BaseTransformer
{
    protected $type = 'sync_logs';

    public function transform(SyncLogs $arr)
    {
        return [
            'id' => (int) $arr->id,
            'integration_id' => (int) $arr->integration_id,
            'integration_record_id' => (int) $arr->integration_record_id,
            'integration_type' => (string) $arr->integration_type,
            'step' => (string) $arr->step,
            'data' =>  $arr->data,
            'result' => $arr->result,
            'created_at' => (string) $arr->created_at->format('Y-m-d H:i:s'),
            'updated_at' => (string) $arr->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
