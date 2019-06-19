<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User\User;
use App\Demand\Demand;
use Faker\Generator as Faker;

$factory->define(Demand::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraphs,
        'description' => $faker->paragraph,
        'content' => $faker->text,
        'location' => $faker->word,
        'postal' => '974'.rand(10, 99),
        'sector' => $faker->word,
        'be_done_at' => $faker->dateTimeBetween($startDate = 'now', $endDate = '1 month', $timezone = null),
        'contracted' => 0,
        'budget' => 2000,
        'owner_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
