<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'title' => $faker->words(2, true),
        'img' => '/default-images//cat-' . random_int(1, 3) . '.jpg',
    ];
});
