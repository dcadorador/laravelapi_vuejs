<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachshipStatusMapping extends Model
{
    //
    protected $table = 'machship_status_mapping';

    protected $fillable = [
        'integration_id',
        'machship_status_id',
        'machship_status',
        'record_status',
        'update_source',
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
