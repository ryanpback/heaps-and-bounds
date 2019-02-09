<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories = App\Models\Post
|--------------------------------------------------------------------------
|
 */

$factory->define(App\Models\Post::class, function (Faker $faker) {
    return [
        'post_content'  => $faker->paragraph . ' ' . $faker->paragraph,
        'pinned'        => false,
        'status'        => 'draft',
        'title'         => $faker->sentence,
    ];
});
