<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\District\District;
use Faker\Generator as Faker;

$factory->define(District::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'sector_id' => 1,
        'commune_id' => 1,
    ];
});
