<?php

namespace App\Candidature;

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User\User;
use App\Demand\Demand;
use Faker\Generator as Faker;

$factory->define(Candidature::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph,
        'owner_id' => factory(User::class)->create(),
        'demand_id' => factory(Demand::class)->create(),
    ];
});
