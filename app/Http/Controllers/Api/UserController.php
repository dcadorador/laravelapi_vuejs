<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\UserTransformer;
use App\Repositories\UserRepository;
use App\Http\Controllers\ApiBaseController;

class UserController extends ApiBaseController
{

    public function __construct(
        UserTransformer $userTransformer,
        UserRepository $userRepository
    ) {
        $this->repository = $userRepository;
        $this->transformer = $userTransformer;

        $this->validations = [
            'name' => 'required|max:30',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required'
        ];
    }

    public function update(Request $request, $id)
    {
        // to bypass own unique email
        $this->validations['email'] .= ',' . $id;
        return parent::update($request, $id);
    }
}
