<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User\User;
use App\Demand\Demand;
use App\Sector\Sector;
use App\Category\Category;
use App\Contract\Contract;
use Faker\Generator as Faker;
use App\Candidature\Candidature;
use Metko\Galera\GlrConversation;

$factory->define(Contract::class, function (Faker $faker) {
    return [
        'demand_owner_id' => function () {
            return factory(User::class)->create()->id;
        },
        'candidature_owner_id' => function () {
            return factory(User::class)->create()->id;
        },
        'demand_id' => function () {
            return factory(Demand::class)->create()->id;
        },
        'candidature_id' => function () {
            return factory(Candidature::class)->create()->id;
        },
        'conversation_id' => function () {
            return factory(GlrConversation::class)->create()->id;
        },
        'category_id' => function () {
            return factory(Category::class)->create()->id;
        },
        'sector_id' => function () {
            return factory(Sector::class)->create()->id;
        },
    ];
});
