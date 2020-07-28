<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Product;
use App\User;
use Faker\Generator as Faker;

$factory->define(App\Rate::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return User::count() > 0 ? User::all()->random() : factory(User::class)->create();
        },
        'product_id' => function () {
            return Product::count() > 0 ? Product::all()->random() : factory(Product::class)->create();
        },
        'rate' => $faker->randomFloat(1, 0, 5),
        'message' => $faker->text(250)
    ];
});
