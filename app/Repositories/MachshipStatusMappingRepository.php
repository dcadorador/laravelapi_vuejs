<?php

namespace App\Repositories;

use App\Models\MachshipStatusMapping;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Hash;

class MachshipStatusMappingRepository extends BaseRepository
{
    public function model()
    {
        return MachshipStatusMapping::class;
    }
}
