<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationMeta extends Model
{
    //
    protected $table = 'integration_meta';

    protected $fillable = [
        'integration_id',
        'meta_key',
        'meta_value'
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
