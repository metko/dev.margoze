<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Sector\Sector;
use Faker\Generator as Faker;

$factory->define(Sector::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => Str::slug($name),
    ];
});
