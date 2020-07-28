<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Address;
use App\User;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'firstName' => $faker->firstName,
        'lastName' => $faker->lastName,
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'country' => $faker->country,
        'dep' => $faker->randomDigitNotNull,
        'gov' => $faker->city,
        'postCode' => $faker->postcode,
        'phoneNumber' => $faker->phoneNumber,
        'notes' => $faker->sentence,
        'userMail' => $faker->email
    ];
});
