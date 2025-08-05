<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    // $faker = Faker\Factory::create('ja_JP');
    return [
        'message' => $faker->realText(20),
        'picture' => $faker->unique()->safeEmail,
        'good' => random_int(0, 99),
        'user_id' => random_int(1, 10),
    ];
});
