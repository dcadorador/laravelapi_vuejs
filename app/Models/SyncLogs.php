<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLogs extends Model
{
    //
    protected $table = 'sync_logs';

    protected $fillable = [
        'integration_id',
        'integration_record_id',
        'integration_type',
        'step',
        'data',
        'result',
    ];

    protected $casts = [
        'data' => 'array',
        'result' => 'array',
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }

    public function integrationRecord()
    {
        return $this->belongsTo(IntegrationRecords::class);
    }
}
