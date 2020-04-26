<?php

namespace App\Transformers;

use App\Models\Integration;

class IntegrationTransformer extends BaseTransformer
{
    protected $type = 'integration';

    public function transform(Integration $arr)
    {
        return [
           'id' => (int) $arr->id,
           'label' => (string) $arr->label,
           'account_id' => (int) $arr->account_id,
           'integration_type' => $arr->integrationType,
           'integration_status' => (string) $arr->integration_status,
           'frequency_mins' => (int) $arr->frequency_mins,
           'last_run' => (string) $arr->last_run ? $arr->last_run : 'none',
           'account' => $arr->account,
           'created_at' => (string) $arr->created_at->format('Y-m-d H:i:s'),
           'updated_at' => (string) $arr->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
