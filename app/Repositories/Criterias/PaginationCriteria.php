<?php

namespace App\Repositories\Criterias;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

use App\Models\User;

class PaginationCriteria implements CriteriaInterface
{
    private $term = 0;

    public function __construct($page)
    {
        if ($page['size'] > 0) {
            $this->term = intval($page['size']) * (intval($page['index']) - 1);
        }
    }

    public function apply($model, RepositoryInterface $repository)
    {
        if ($this->term <= 0) {
            return $model;
        }

        return $model->offset($this->term);
    }
}
