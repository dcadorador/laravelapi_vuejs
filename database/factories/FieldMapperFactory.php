<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\FieldMapper;
use Faker\Generator as Faker;

$factory->define(FieldMapper::class, function (Faker $faker) {
    return [
        'integration_id' => 1,
        'data_direction' => 'to_machship',
        'machship_field' => 'dgsDeclaration',
        'map_type' => 'customfield',
        'source_field' => 'custbody_contains_dgs',
        'data_conversion_type' => null,
        'data_conversion_value' => null
    ];
});
