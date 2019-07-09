<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Category\Category;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => Str::slug($name),
    ];
});
