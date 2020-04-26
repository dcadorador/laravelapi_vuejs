<?php

namespace App\Transformers;

use App\Models\Account;

class AccountTransformer extends BaseTransformer
{
    protected $type = 'account';
    protected $is_detail = false;

    public function transform(Account $arr)
    {
        return [
           'id' => (int) $arr->id,
           'client_name' => (string) $arr->client_name,
           'client_notes' => (string) $arr->client_notes,
           'user_id' => (int) $arr->user_id ,
           'user' => $arr->user,
           'integrations' => $this->is_detail ? $arr->integrations : null,
           'integration_total' => $arr->integrations->count(),
           'created_at' => (string) $arr->created_at->format('Y-m-d H:i:s'),
           'updated_at' => (string) $arr->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function enableDetails()
    {
        $this->is_detail = true;
    }
}
