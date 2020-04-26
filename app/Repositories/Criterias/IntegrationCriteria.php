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
class IntegrationCriteria implements CriteriaInterface
{

    private $keywords = '';

    public function __construct(Request $request)
    {
        if (!empty($request->input('search'))) {
            $this->keywords = $request->input('search');
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

        if (!empty($this->keywords)) {
            // if (strtolower($this->keywords) == 'draft') {
            //     $model = $model->orWhere('is_final', 0);
            // } elseif (strtolower($this->keywords) == 'final') {
            //     $model = $model->orWhere('is_final', 1);
            // }
        }

        return $model;
    }
}
