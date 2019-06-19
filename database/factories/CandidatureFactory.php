<?php

namespace App\Candidature;

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User\User;
use Faker\Generator as Faker;

$factory->define(Candidature::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph,
        'owner_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
