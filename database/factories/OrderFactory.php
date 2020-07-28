<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Address;
use App\Order;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return User::count() > 0 ? User::all()->random()->id : factory(User::class)->create();
        },
        'address_id' => function () {
            return Address::count() > 0 ? Address::all()->random()->id : factory(Address::class)->create();
        },
        'status' => Arr::random(['pending', 'processing', 'completed']),
        'total' => $faker->randomFloat(2, 50, 29963),
        'qty' => random_int(5, 531564),
        'paymentStatus' => $faker->boolean,
        'paymentMethod' => Arr::random(['accept', 'onDeliver']),
        // 'userMail' => random_int(0, 1) ? $faker->email : null,
        'orderNum' => bin2hex(random_bytes(5))
    ];
});
