<?php

namespace App\Transformers;

use App\Models\MachshipStatusMapping;

class MachshipStatusMappingTransformer extends BaseTransformer
{
    protected $type = 'machship_status_mapping';

    public function transform(MachshipStatusMapping $arr)
    {
        return [
            'id' => (int) $arr->id,
            'integration_id' => (int) $arr->integration_id,
            'machship_status_id' => (int) $arr->machship_status_id,
            'machship_status' => (string) $arr->machship_status,
            'record_status' => (string) $arr->record_status,
            'update_source' => (boolean) $arr->update_source,
            'created_at' => (string) $arr->created_at->format('Y-m-d H:i:s'),
            'updated_at' => (string) $arr->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
