<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\ValueLookups;

class ValueLookupsRepository extends BaseRepository
{
    public function model()
    {
        return ValueLookups::class;
    }
}
