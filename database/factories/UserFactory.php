<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'username' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'biography' => $faker->text,
        'adress_1' => $faker->address,
        'adress_2' => $faker->country,
        'sector' => $faker->word,
        'postal' => '974'.rand(10, 99),
        'city' => $faker->city,
        'avatar' => $faker->imageUrl,
        'phone_1' => $faker->e164PhoneNumber,
        'phone_2' => $faker->e164PhoneNumber,
        'date_of_birth' => $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null),
        'vehiculable' => 1,
        'trusted' => 0,
        'profesionnal' => 0,
        'subscriber' => 0,
        'password' => 'password', // password
        'remember_token' => Str::random(10),
    ];
});
