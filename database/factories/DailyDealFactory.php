<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DailyDeal;
use App\Product;
use Faker\Generator as Faker;

$factory->define(DailyDeal::class, function (Faker $faker) {
    return [
        'product_slug' => function() {
            return Product::count() > 0 ? (Product::all()->random())->slug : (factory(Product::class)->create())->slug;
        }
    ];
});
