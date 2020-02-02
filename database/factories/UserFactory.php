<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

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
        'avatar' => '',
        'phone_1' => $faker->e164PhoneNumber,
        'phone_2' => $faker->e164PhoneNumber,
        'date_of_birth' => $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null),
        'vehiculable' => false,
        'suspended' => false,
        'profesionnal' => false,
        'subscriber' => false,
        'password' => 'password', // password
        'remember_token' => Str::random(10),
    ];
});
