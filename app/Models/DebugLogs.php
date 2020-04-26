<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebugLogs extends Model
{

    const STEP_WF_1 = 1; // WORKFLOW 1 - Job Due Sync and Create
    const STEP_WF_2 = 2; // WORKFLOW 2 - Job Get data
    const STEP_WF_3 = 3; // WORKFLOW 3 - Job Process data
    const STEP_WF_4 = 4; // WORKFLOW 4 - Job Send to Machship
    const STEP_WF_5 = 5; // WORKFLOW 5 - Job Update Source

    //
    protected $table = 'debug_logs';

    protected $fillable = [
        'integration_id',
        'integration_sync_id',
        'integration_record_id',
        'sync_step',
        'data',
    ];
}
