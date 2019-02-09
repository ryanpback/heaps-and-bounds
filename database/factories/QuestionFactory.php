<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories - App\Models\Question
|--------------------------------------------------------------------------
|
 */

$factory->define(App\Models\Question::class, function (Faker $faker) {
    return [
        'question_content'  => $faker->paragraph . ' ' . $faker->paragraph,
        'title'             => $faker->sentence,
    ];
});
