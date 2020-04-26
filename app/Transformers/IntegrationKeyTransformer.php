<?php

namespace App\Transformers;

use App\Models\FieldMapper;

class IntegrationKeyTransformer extends BaseTransformer
{
    protected $type = 'field_mapper';

    public function transform(FieldMapper $arr)
    {
        return [
           'id' => (int) $arr->id,
           'integration_id' => (int) $arr->integration_id,
           'key_type' => (string) $arr->key_type,
           'key_data' => (string) $arr->key_data,
           'expiry' => (string) $arr->expiry,
           'created_at' => (string) $arr->created_at->format('Y-m-d H:i:s'),
           'updated_at' => (string) $arr->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
