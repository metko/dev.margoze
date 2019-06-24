<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Demand\DemandSector;
use Faker\Generator as Faker;

$factory->define(DemandSector::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => Str::slug($name),
    ];
});
