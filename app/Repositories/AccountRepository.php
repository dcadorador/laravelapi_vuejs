<?php

namespace App\Repositories;

use App\Models\Account;
use Prettus\Repository\Eloquent\BaseRepository;

class AccountRepository extends BaseRepository
{
    public function model()
    {
        return Account::class;
    }

    protected $fieldSearchable = [
        'id',
        'client_name' => 'like',
        'user.name' => 'like'
    ];

    public function boot()
    {
        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    }
}
