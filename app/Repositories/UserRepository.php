<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

    protected $fieldSearchable = [
        'id',
        'email' => 'like',
        'name' => 'like'
    ];

    public function boot()
    {
        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    }


    public function create($data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        
        $result = parent::create($data);

        if (isset($data['roles'])) {
            $this->updateRoles($result, $data['roles']);
        }

        return $result;
    }

    public function update($data, $id)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $roles = $data['roles'];
        $result = parent::update($data, $id);

        $result->setRoles([]);
        $this->updateRoles($result, $roles);

        return $result;
    }

    private function updateRoles($data, $roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                $data->addRole($role);
            }
        } else {
            $data->addRole($roles);
        }

        $data->save();
    }
}
