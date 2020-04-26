<?php

namespace App\Transformers;

use App\Models\FieldMapper;

class FieldMapperTransformer extends BaseTransformer
{
    protected $type = 'field_mapper';

    public function transform(FieldMapper $arr)
    {
        return [
           'id' => (int) $arr->id,
           'integration_id' => (int) $arr->integration_id,
           'data_direction' => (string) $arr->data_direction,
           'machship_field' => (string) $arr->machship_field,
           'map_type' => (string) $arr->map_type,
           'source_field' => (string) $arr->source_field,
           'data_conversion_type' => (string) $arr->data_conversion_type,
           'data_conversion_value' => (string) $arr->data_conversion_value,
           'created_at' => (string) $arr->created_at->format('Y-m-d H:i:s'),
           'updated_at' => (string) $arr->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
