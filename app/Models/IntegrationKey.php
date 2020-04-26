<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationKey extends Model
{
    //
    protected $table = 'integration_keys';

    protected $fillable = [
        'integration_id',
        'key_type',
        'key_data',
        'expiry'
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
