<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\SyncLogs;

class SyncLogsRepository extends BaseRepository
{
    public function model()
    {
        return SyncLogs::class;
    }

    public function addLogByRecord($record, $step, $data = [], $result = [])
    {
        $integration_id = $record->integration_id;
        $integration_record_id = $record->id;
        $integration_type = $record->integration->integration_type;
        $this->model->create([
            'integration_id' => $integration_id,
            'integration_record_id' => $integration_record_id,
            'integration_type' => $integration_type,
            'step' => $step,
            'data' => $data,
            'result' => $result
        ]);
    }
}
