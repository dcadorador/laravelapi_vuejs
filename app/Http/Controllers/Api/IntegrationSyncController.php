<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\IntegrationSyncsTransformer;
use App\Transformers\IntegrationSyncsDetailTransformer;
use App\Repositories\IntegrationSyncsRepository;
use App\Http\Controllers\ApiBaseController;

class IntegrationSyncController extends ApiBaseController
{

    public function __construct(
        IntegrationSyncsTransformer $integrationSyncsTransformer,
        IntegrationSyncsRepository $integrationSyncsRepository
    ) {
        $this->repository = $integrationSyncsRepository;
        $this->transformer = $integrationSyncsTransformer;

        $this->validations = [];
    }

    public function show($id)
    {
        // use transformer to view detail integration
        $this->transformer = new IntegrationSyncsDetailTransformer;
        return parent::show($id);
    }
}
