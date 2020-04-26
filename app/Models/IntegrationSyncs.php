<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationSyncs extends Model
{
    //
    const SYNC_STATUS_PENDING = 'PENDING';
    const SYNC_STATUS_PROCESSING = 'PROCESSING';
    const SYNC_STATUS_ERROR = 'ERROR';
    const SYNC_STATUS_SKIPPED = 'SKIP';
    const SYNC_STATUS_PROCESSED = 'PROCESSED';
    const SYNC_STATUS_COMPLETED = 'COMPLETED';

    protected $table = 'integration_syncs';

    protected $fillable = [
        'integration_id',
        'period_start',
        'period_end',
        'record_found',
        'sync_status',
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }

    public function integrationRecords()
    {
        return $this->hasMany(IntegrationRecords::class, 'integration_sync_id');
    }

    public function scopePending($query)
    {
        return $query->where('sync_status', self::SYNC_STATUS_PROCESSING);
    }
}
