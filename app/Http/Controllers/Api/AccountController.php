<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\AccountTransformer;
use App\Repositories\AccountRepository;
use App\Http\Controllers\ApiBaseController;

class AccountController extends ApiBaseController
{

    public function __construct(
        AccountTransformer $accountTransformer,
        AccountRepository $accountRepository
    ) {
        $this->repository = $accountRepository;
        $this->transformer = $accountTransformer;

        $this->validations = [
            'client_name' => 'required|max:30',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function show($id)
    {
        $this->transformer->enableDetails();
        return parent::show($id);
    }
}
