<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\IntegrationSourceFilterTransformer;
use App\Transformers\IntegrationDetailsTransformer;
use App\Repositories\IntegrationSourceFilterRepository;
use App\Repositories\IntegrationRepository;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Support\Arr;

class IntegrationFilterController extends ApiBaseController
{

    public function __construct(
        IntegrationSourceFilterTransformer $integrationSourceFilterTransformer,
        IntegrationSourceFilterRepository $integrationSourceFilterRepository
    ) {
        $this->repository = $integrationSourceFilterRepository;
        $this->transformer = $integrationSourceFilterTransformer;

        $this->validations = [
            'integration_id' => 'required|exists:integrations,id',
            'filter_key' => 'required',
            'filter_value' => 'required'
        ];
    }

    /**
     * Gets the filter options.
     *
     * @param      \Illuminate\Http\Request  $request         The request
     * @param      integer                   $id              The integration identifier
     *
     * @return     array                     The filter options.
     */
    public function getFilterOptions(Request $request, $id)
    {
        $integration_repository = new IntegrationRepository(app());
        $integration = $integration_repository->find($id);
        $options = $integration->integrationType->getFilterOptions();

        return $options;
    }

    /**
     * Resets filter option base from integration id
     *
     * @param      \Illuminate\Http\Request  $request         The request
     * @param      integer                   $id              The integration identifier
     *
     * @return     IntegrationDetailTransformer
     */
    public function reset(Request $request, $id)
    {
        $this->transformer = new IntegrationDetailsTransformer();
        $integration_repository = new IntegrationRepository(app());
        $integration_repository->resetFilters($id);
        $integration = $integration_repository->find($id);

        return $this->success($integration);
    }


    public function bulkStore(Request $request)
    {
        $all = Arr::collapse($request->all());

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
