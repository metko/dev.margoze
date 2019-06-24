<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User\User;
use App\Demand\Demand;
use App\Contract\Contract;
use Faker\Generator as Faker;
use App\Candidature\Candidature;

$factory->define(Contract::class, function (Faker $faker) {
    return [
        'demander_owner_id' => function () {
            return factory(User::class)->create()->id;
        },
        'candidature_owner_id' => function () {
            return factory(User::class)->create()->id;
        },
        'demand_id' => function () {
            return factory(Demand::class)->create()->id;
        },
        'candidature_id' => function () {
            return factory(Candidature::class)->create()->id;
        },
    ];
});
