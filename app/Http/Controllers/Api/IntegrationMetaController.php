<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\IntegrationMetaTransformer;
use App\Transformers\IntegrationDetailsTransformer;
use App\Repositories\IntegrationMetaRepository;
use App\Repositories\IntegrationRepository;
use App\Http\Controllers\ApiBaseController;

class IntegrationMetaController extends ApiBaseController
{

    public function __construct(
        IntegrationMetaTransformer $integrationMetaTransformer,
        IntegrationMetaRepository $integrationMetaRepository
    ) {
        $this->repository = $integrationMetaRepository;
        $this->transformer = $integrationMetaTransformer;

        $this->validations = [
            'integration_id' => 'required|exists:integrations,id',
            'meta_key' => 'required',
            'meta_value' => 'required'
        ];
    }


    public function bulkStore(Request $request)
    {
        $all = $request->all();
        $integration_id = 0;
        foreach ($all as $row) {
            $checker = [];
            
            if (isset($row['id'])) {
                $this->repository->update($row, $row['id']);
            } else {
                $this->repository->create($row);
            }
            $integration_id = $row['integration_id'];
        }

        $this->transformer = new IntegrationDetailsTransformer();
        $repository = new IntegrationRepository(app());
        $result = $repository->find($integration_id);
        return $this->success($result);
    }
}
