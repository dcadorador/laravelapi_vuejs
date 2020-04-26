<?php

namespace App\Transformers;

use App\Models\IntegrationSyncs;

class IntegrationSyncsTransformer extends BaseTransformer
{
    protected $type = 'integration_syncs';

    public function transform(IntegrationSyncs $arr)
    {
        return [
           'id' => (int) $arr->id,
           'integration_id' => (int) $arr->integration_id,
           'integration' => $arr->integration,
           'period_start' => (string) date('Y-m-d H:i:s', strtotime($arr->period_start)),
           'period_end' => (string) date('Y-m-d H:i:s', strtotime($arr->period_end)),
           'records_found' => (int) $arr->records_found,
           'sync_status' => (string) $arr->sync_status,
           'created_at' => (string) $arr->created_at ? $arr->created_at->format('Y-m-d H:i:s') : '',
           'updated_at' => (string) $arr->updated_at ? $arr->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
