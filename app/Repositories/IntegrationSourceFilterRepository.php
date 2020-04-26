<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\IntegrationSourceFilter;

class IntegrationSourceFilterRepository extends BaseRepository
{
    public function model()
    {
        return IntegrationSourceFilter::class;
    }
}
