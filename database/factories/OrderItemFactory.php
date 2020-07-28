<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use App\OrderItem;
use App\Product;
use Faker\Generator as Faker;

$factory->define(OrderItem::class, function (Faker $faker) {
    $price = $faker->randomFloat(2, 1000, 10000);
    $amount = random_int(1, 25);
    return [
        'product_id' => function () {
            return Product::count() > 0 ? Product::all()->random()->id : factory(Product::class)->create();
        },
        'order_id' => function () {
            return Order::count() > 0 ? Order::all()->random()->id : factory(Order::class)->create();
        },
        'price' => $price,
        'qty' => $amount,
        'size' => random_int(1, 3),
        'color' => random_int(1, 3),
        'sub_total' => round($price * $amount, 2),
    ];
});
