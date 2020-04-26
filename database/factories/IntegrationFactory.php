<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Integration;
use Faker\Generator as Faker;

$factory->define(Integration::class, function (Faker $faker) {
    return [
        'id' => 1,
        'label' => 'Test Netsuite',
        'account_id' => 1,
        'integration_type_id' => 1,
        'integration_status' => 'active',
        'frequency_mins' => 1,
        'last_run' => null,
        'master_consignment_type' => 'pending'
    ];
});
