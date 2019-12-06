<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Place;
use Faker\Generator as Faker;

$factory->define(Place::class, function (Faker $faker) {
    return [
        'city' => $faker->city(),
        'address' => $faker->address(),
        'timezone' => $faker->timezone()
    ];
});
