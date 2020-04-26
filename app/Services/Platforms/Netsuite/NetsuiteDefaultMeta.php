<?php
namespace App\Services\Platforms\Netsuite;

trait NetsuiteDefaultMeta
{

    public static function defaultMeta()
    {
        return [
            ['meta_key' => 'NETSUITE_ENDPOINT', 'meta_value' => ''],
            ['meta_key' => 'NETSUITE_ACCOUNT', 'meta_value' => ''],
            ['meta_key' => 'NETSUITE_CONSUMER_KEY', 'meta_value' => ''],
            ['meta_key' => 'NETSUITE_CONSUMER_SECRET', 'meta_value' => ''],
            ['meta_key' => 'NETSUITE_TOKEN', 'meta_value' => ''],
            ['meta_key' => 'NETSUITE_TOKEN_SECRET', 'meta_value' => ''],
            ['meta_key' => 'NETSUITE_WEBSERVICES_HOST', 'meta_value' => ''],
            ['meta_key' => 'NETSUITE_APP_ID', 'meta_value' => ''],
            ['meta_key' => 'NETSUITE_OBJECT_TYPE', 'meta_value' => ''],
        ];
    }
}
