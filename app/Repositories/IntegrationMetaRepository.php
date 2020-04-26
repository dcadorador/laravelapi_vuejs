<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\IntegrationMeta;

class IntegrationMetaRepository extends BaseRepository
{
    public function model()
    {
        return IntegrationMeta::class;
    }
}
