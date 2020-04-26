<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\FieldMapper;

class FieldMapperRepository extends BaseRepository
{
    public function model()
    {
        return FieldMapper::class;
    }

    public function getOptions()
    {
        $options['directions'] = FieldMapper::DATA_DIRECTIONS;
        $options['conversion_types'] = FieldMapper::DATA_CONVERSION_TYPES;
        $options['map_types'] = FieldMapper::DATA_MAP_TYPES;

        return $options;
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
