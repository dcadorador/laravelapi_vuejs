<?php

namespace App\Transformers;

use App\Models\Integration;

class IntegrationMetaTransformer extends BaseTransformer
{
    protected $type = 'integration_meta';

    public function transform(Integration $arr)
    {
        return [
           'id' => (int) $arr->id,
           'integration_id' => (int) $arr->integration_id,
           'meta_key' => (string) $arr->meta_key,
           'meta_value' => (string) $arr->meta_value,
           'created_at' => (string) $arr->created_at->format('Y-m-d H:i:s'),
           'updated_at' => (string) $arr->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
