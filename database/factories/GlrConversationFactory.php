<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Metko\Galera\GlrConversation;

$factory->define(GlrConversation::class, function (Faker $faker) {
    $name = $faker->word;

    return [
       'closed' => false,
    ];
});
