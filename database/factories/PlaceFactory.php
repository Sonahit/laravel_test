<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Place;
use Faker\Generator as Faker;

$factory->define(Place::class, function (Faker $faker) {
    return [
        'city' => str_replace(' ', '', $faker->city()),
        'address' => $faker->address(),
        'timezone' => $faker->timezone(),
        'startHours' => $faker->numberBetween(8, 14),
        'endHours' => $faker->numberBetween(15, 20)
    ];
});
