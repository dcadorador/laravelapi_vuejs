<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\IntegrationKey;

class IntegrationKeysRepository extends BaseRepository
{
    public function model()
    {
        return IntegrationKey::class;
    }
}
