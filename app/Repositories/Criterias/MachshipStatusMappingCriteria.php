<?php

namespace App\Repositories\Criterias;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class MachshipStatusMappingCriteria.
 *
 * @package namespace App\Criteria;
 */
class MachshipStatusMappingCriteria implements CriteriaInterface
{

    private $integration_id;

    public function __construct(Request $request)
    {
        if (!empty($request->input('integration_id'))) {
            $this->integration_id = $request->input('integration_id');
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

        if (!empty($this->integration_id)) {
            $model = $model->where('integration_id', $this->integration_id);
        }

        return $model;
    }
}
