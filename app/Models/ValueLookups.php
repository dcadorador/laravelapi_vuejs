<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValueLookups extends Model
{
    //
    protected $table = 'value_lookups';

    protected $fillable = [
        'integration_id',
        'data_direction',
        'machship_field',
        'from_value',
        'from_label',
        'to_value',
        'to_label',
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
