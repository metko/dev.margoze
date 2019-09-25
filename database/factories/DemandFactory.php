<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User\User;
use App\Demand\Demand;
use App\Sector\Sector;
use App\Category\Category;
use Faker\Generator as Faker;

$factory->define(Demand::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraphs,
        'description' => $faker->paragraph,
        'content' => $faker->text,
        'postal' => '974'.rand(10, 99),
        'status' => 'default',
        'valid_until' => now()->addMonths(1),
        'be_done_at' => $faker->dateTimeBetween($startDate = 'now', $endDate = '1 month', $timezone = null),
        'contracted' => 0,
        'budget' => 2000,
        'category_id' => function () {
            return factory(Category::class)->create()->id;
        },
        'sector_id' => function () {
            return factory(Sector::class)->create()->id;
        },
        'owner_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
