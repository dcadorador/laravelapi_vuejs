<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationSourceFilter extends Model
{
    //
    protected $table = 'integration_source_filters';

    protected $fillable = [
        'integration_id',
        'filter_key',
        'filter_value',
        'integration_source_filter_id'
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
