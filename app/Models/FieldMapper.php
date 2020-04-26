<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldMapper extends Model
{
    //
    protected $table = 'field_mapper';

    const DATA_DIRECTIONS = [
        'to_machship' => 'To Machship',
        'to_source' => 'To Source',
    ];

    const DATA_CONVERSION_TYPES = [
        'none' => 'NONE',
        'constant' => 'CONSTANT',
        'function' => 'FUNCTION',
        'lookup' => 'LOOKUP',
        'custom_function' => 'CUSTOM_FUNCTION'
    ];

    const DATA_MAP_TYPES = [
        'skip' => 'SKIP',
        'customfield' => 'CUSTOMFIELD',
        'direct' => 'DIRECT',
        'direct_simple' => 'DIRECT_SIMPLE',
        'array' => 'ARRAY',
    ];

    protected $fillable = [
        'integration_id',
        'data_direction',
        'machship_field',
        'map_type',
        'source_field',
        'data_conversion_type',
        'data_conversion_value'
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
