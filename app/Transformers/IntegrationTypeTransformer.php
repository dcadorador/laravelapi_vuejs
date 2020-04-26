<?php

namespace App\Transformers;

use App\Models\IntegrationType;

class IntegrationTypeTransformer extends BaseTransformer
{
    protected $type = 'integration_type';

    public function transform(IntegrationType $arr)
    {
        return [
            'id' => (int) $arr->id,
            'name' => (string) $arr->name,
            'display_name' => (string) $arr->display_name,
            'is_active' => (boolean) $arr->is_active,
        ];
    }
}
