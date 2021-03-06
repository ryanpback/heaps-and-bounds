<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories - App\Models\User
|--------------------------------------------------------------------------
|
*/

$factory->defineAs(App\Models\User::class, 'new', function ($faker) {
    return [
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'active'            => 1,
        'password'          => Hash::make('secret'),
        'remember_token'    => str_random(10),
    ];
});

$factory->defineAs(App\Models\User::class, 'full', function ($faker) {
    return [
        'email'             => $faker->unique()->safeEmail,
        'first_name'        => $faker->unique()->firstName,
        'last_name'         => $faker->unique()->lastName,
        'email_verified_at' => now(),
        'active'            => 1,
        'password'          => Hash::make('secret'),
        'remember_token'    => str_random(10),
        'username'          => $faker->unique()->username
    ];
});
