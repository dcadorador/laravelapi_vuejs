<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    protected $type;

    public function getType()
    {
        return $this->type;
    }
}
