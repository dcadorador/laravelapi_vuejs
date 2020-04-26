<?php

namespace App\Transformers;

class TokenTransformer extends BaseTransformer
{
    protected $type = 'token';

    public function transform($arr)
    {
        return [
            'id' => '0',
            'token' => $arr->token,
            'type' => $arr->type,
            'expiry' => $arr->expiry,
        ];
    }
}
