<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Commune\Commune;
use Faker\Generator as Faker;

$factory->define(Commune::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'sector_id' => 1,
    ];
});
