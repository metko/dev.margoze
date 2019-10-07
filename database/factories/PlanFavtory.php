<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Plan\Plan;
use Faker\Generator as Faker;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'name' => '',
        'slug' => '',
        'amount' => '',
        'stripe_id' => '',
    ];
});
