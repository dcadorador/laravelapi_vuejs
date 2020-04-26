<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\IntegrationType;

class IntegrationTypeRepository extends BaseRepository
{
    public function model()
    {
        return IntegrationType::class;
    }
}
