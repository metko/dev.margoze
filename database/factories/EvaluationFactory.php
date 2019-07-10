<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\Evaluation\Evaluation;

$factory->define(Evaluation::class, function (Faker $faker) {
    return [
        'comment' => $faker->text,
        'note' => rand(0, 5),
    ];
});
