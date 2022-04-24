<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return User::count() > 0 ? User::all()->random()->id : factory(User::class)->create();
        },
        'title' => $faker->sentence,
        'price' => $faker->randomFloat(2, 100, 10000),
        'save' => round($faker->randomFloat(2, 1, 99), 2),
        'qty' => random_int(90, 955),
        'info' => $faker->paragraph(5),
        'colors' => Arr::random(
            ['red', 'blue', 'black', 'green', 'yellow', 'pink', 'white', 'teal'],
            3
        ),
        'sizes' => Arr::random(
            ['x', 'xl', 'xxl', 'xxxl', 'xxxxl'],
            3
        ),
        'images' => [
            '/default-images//' . random_int(1, 5) . '.jpg',
            '/default-images//' . random_int(1, 5) . '.jpg',
            '/default-images//' . random_int(1, 5) . '.jpg',
        ],
        'featured' => (bool)random_int(0, 1)
    ];
});
