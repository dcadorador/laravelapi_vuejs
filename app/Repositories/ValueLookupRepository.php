<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\ValueLookups;

class ValueLookupRepository extends BaseRepository
{
    public function model()
    {
        return ValueLookups::class;
    }

    public function findyByIntegIdToMachship($integration_id)
    {
        return $this->model->where([
            'data_direction' => 'to_machship',
            'integration_id' => $integration_id
        ])->get();
    }

    public function findyByIntegIdFromMachship($integration_id)
    {
        return $this->model->where([
            'data_direction' => 'from_machship',
            'integration_id' => $integration_id
        ])->get();
    }

    public function removeByIds($ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
