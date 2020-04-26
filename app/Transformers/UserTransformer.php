<?php

namespace App\Transformers;

use App\Models\User;

class UserTransformer extends BaseTransformer
{
    protected $type = 'user';

    public function transform(User $arr)
    {
        return [
           'id' => (int) $arr->id,
           'name' => (string) $arr->name,
           'email' => (string) $arr->email,
           'roles' => (array) $arr->roles,
           'created_at' => (string) $arr->created_at->format('Y-m-d H:i:s'),
           'updated_at' => (string) $arr->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
