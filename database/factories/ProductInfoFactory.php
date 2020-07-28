<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProductInfo;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(ProductInfo::class, function (Faker $faker) {
    $data = [];
    foreach (range(1, 15) as $i) {
        $data[$faker->words(3, true)] = $faker->word;
        if (random_int(0, 1) === 1) {
            $data[$faker->words(3, true)] = $faker->randomFloat(1, 1, 250) .' '. Arr::random(['litre', 'meter', 'kg', 'gram']);
        } else {
            $data[$faker->words(3, true)] = !!random_int(0, 1);
        }
    }
    return [
        'more' => $data
    ];
});
