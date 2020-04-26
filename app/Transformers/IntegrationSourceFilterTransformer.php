<?php

namespace App\Transformers;

use App\Models\IntegrationSourceFilter;

class IntegrationSourceFilterTransformer extends BaseTransformer
{
    protected $type = 'integration_source_filter';

    public function transform(IntegrationSourceFilter $arr)
    {
        return [
           'id' => (int) $arr->id,
           'integration_id' => (int) $arr->integration_id,
           'filter_key' => (string) $arr->meta_key,
           'filter_value' => (string) $arr->meta_value,
           'created_at' => (string) $arr->created_at->format('Y-m-d H:i:s'),
           'updated_at' => (string) $arr->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
