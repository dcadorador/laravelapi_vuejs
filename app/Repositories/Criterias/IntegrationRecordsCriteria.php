<?php

namespace App\Repositories\Criterias;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class IntegrationCriteria.
 *
 * @package namespace App\Criteria;
 */
class IntegrationRecordsCriteria implements CriteriaInterface
{

    private $integration_sync_id = 0;

    private $integration_ids;

    public function __construct(Request $request)
    {
        if (!empty($request->input('integration_sync_id'))) {
            $this->integration_sync_id = $request->input('integration_sync_id');
        }

        if (!empty($request->input('integration_ids'))) {
            $this->integration_ids = $request->input('integration_ids');
        }
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {

        if (!empty($this->integration_sync_id)) {
            $model = $model->where('integration_sync_id', $this->integration_sync_id);
        }

        if (!empty($this->integration_ids)) {
            $model = $model->whereIn('integration_id', $this->integration_ids);
        }

        return $model;
    }
}
