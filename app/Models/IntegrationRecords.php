<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationRecords extends Model
{
    //
    const RECORD_STATUS_PENDING = 'PENDING';
    const RECORD_STATUS_PENDING_MACHSHIP = 'PENDING_MACHSHIP';
    const RECORD_STATUS_ERROR = 'ERROR';
    const RECORD_STATUS_PROCESSED = 'PROCESSED';
    const RECORD_STATUS_RE_PROCESS = 'REPROCESS';
    const RECORD_STATUS_PROCESSING = 'PROCESSING';
    const RECORD_STATUS_MAPPING_ERROR = 'MAPPING_ERROR';
    const RECORD_STATUS_SKIPPED = 'SKIPPED';
    const RECORD_STATUS_MACHSHIP_ERROR = 'MACHSHIP_ERROR';
    const RECORD_STATUS_PENDING_UPDATE = 'PENDING_UPDATE';
    const RECORD_STATUS_COMPLETED = 'COMPLETED';

    const RECORD_CONSIGNMENT_TYPE_PENDING = 'PENDING';
    const RECORD_CONSIGNMENT_TYPE_MANIFEST = 'MANIFEST';
    const RECORD_CONSIGNMENT_STATUS_PENDING = 'PENDING';
    const RECORD_CONSIGNMENT_STATUS_UNMANIFESTED = 'UNMANIFESTED';

    protected $table = 'integration_records';

    protected $casts = [
        'source_data' => 'array',
        'machship_payload' => 'array'
    ];

    protected $fillable = [
        'integration_id',
        'integration_sync_id',
        'source_id',
        'source_data',
        'machship_id',
        'machship_consignment_status',
        'machship_payload',
        'record_status',
        'consignment_type',
        'process_after'
    ];

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }

    public function integrationSyncs()
    {
        return $this->belongsTo(IntegrationSyncs::class, 'integration_sync_id');
    }

    public function scopePending($query)
    {
        return $query->where('record_status', self::RECORD_STATUS_PENDING);
    }

    public function debugLogs()
    {
        return $this->hasMany(DebugLogs::class, 'integration_record_id');
    }

    public function syncLogs()
    {
        return $this->hasMany(SyncLogs::class, 'integration_record_id');
    }
}
