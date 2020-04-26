<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ValueLookups;
use Faker\Generator as Faker;

$factory->define(ValueLookups::class, function (Faker $faker) {
    return [
        'id' => 1,
        'integration_id' => 1,
        'machship_field' => 'company',
        'from_value' => '',
        'from_label' => 'netsuiteCompany',
        'to_value' => 'netsuite',
        'to_label' => 'machshipCompany'
    ];
});
