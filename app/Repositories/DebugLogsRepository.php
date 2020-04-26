<?php

namespace App\Repositories;

use App\Models\DebugLogs;
use Prettus\Repository\Eloquent\BaseRepository;

class DebugLogsRepository extends BaseRepository
{
    public function model()
    {
        return DebugLogs::class;
    }

    public function addLogByRecord($record, $step, $data = [])
    {
        $integration_id = $record->integration_id;
        $sync_id = $record->integration_sync_id;
        $record_id = $record->id;
        
        $this->model->create([
            'integration_id' => $integration_id,
            'integration_sync_id' => $sync_id,
            'integration_record_id' => $record_id,
            'sync_step' => $step,
            'data' => $data
        ]);
    }
}
