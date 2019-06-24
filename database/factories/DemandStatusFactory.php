<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Illuminate\Support\Str;
use App\Demand\DemandStatus;
use Faker\Generator as Faker;

$factory->define(DemandStatus::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => Str::slug($name),
    ];
});
