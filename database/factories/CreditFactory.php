<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Credit;
use Faker\Generator as Faker;

$factory->define(Credit::class, function (Faker $faker) {
    return [
        'demands_valid_for' => 7,
        'urgence_status_count' => 0,
        'photos_demand_count' => 1,
        'offers_per_month' => 0,
        'offers_valid_for' => 0,
        'candidatures_count' => 15,
        'contracts_count' => 3,
    ];
});
