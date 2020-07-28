<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cart;
use App\CartItem;
use App\Product;
use Faker\Generator as Faker;

$factory->define(CartItem::class, function (Faker $faker) {
    $price = $faker->randomFloat(2, 1000, 100000);
    $amount = random_int(1, 2555);
    return [
        'cart_id' => function () {
            return Cart::count() > 0 ? Cart::all()->random() : factory(Cart::class)->create();
        },
        'product_id' => function () {
            // return Product::count() > 0 ? Product::all()->random() : factory(Product::class)->create();
            return factory(Product::class)->create();
        },
        'price' => $price,
        'qty' => $amount,
        'size' => random_int(1, 3),
        'color' => random_int(1, 3)
    ];
});
