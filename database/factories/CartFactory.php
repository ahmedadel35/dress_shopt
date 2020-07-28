<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cart;
use App\Product;
use App\User;
use Faker\Generator as Faker;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return User::count() > 0 ? User::all()->random() : factory(User::class)->create();
        },
        'instance' => null
    ];
});
