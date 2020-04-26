<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\IntegrationTypeTransformer;
use App\Repositories\IntegrationTypeRepository;
use App\Http\Controllers\ApiBaseController;

class IntegrationTypeController extends ApiBaseController
{

    public function __construct(
        IntegrationTypeTransformer $integrationTypeTransformer,
        IntegrationTypeRepository $integrationTypeRepository
    ) {
        $this->repository = $integrationTypeRepository;
        $this->transformer = $integrationTypeTransformer;

        $this->validations = [
            'name' => 'required',
        ];
    }
}
