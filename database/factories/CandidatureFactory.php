<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Candidature;
use Faker\Generator as Faker;

$factory->define(Candidature::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph,
        'owner_id' => factory(\App\User::class)->create(),
        'demand_id' => factory(\App\Demand::class)->create(),
    ];
});
