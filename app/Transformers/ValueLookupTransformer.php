<?php

namespace App\Transformers;

use App\Models\FieldMapper;

class ValueLookupTransformer extends BaseTransformer
{
    protected $type = 'value_lookup';

    public function transform(FieldMapper $arr)
    {
        return [
           'id' => (int) $arr->id,
           'integration_id' => (int) $arr->integration_id,
           'machship_field' => (string) $arr->machship_field,
           'from_value' => (string) $arr->from_value,
           'from_label' => (string) $arr->from_label,
           'to_value' => (string) $arr->to_value,
           'to_label' => (string) $arr->to_label,
           'created_at' => (string) $arr->created_at->format('Y-m-d H:i:s'),
           'updated_at' => (string) $arr->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
