<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Booking;
use Faker\Generator as Faker;

$factory->define(Booking::class, function (Faker $faker) {
    $start = $faker->unique()->dateTimeBetween('now', '+15 days');
    $end = $faker->unique()->dateTimeBetween('now +15 days', 'now +15 days +2 hours');
    return [
        'bookingDateStart' => $start->format('Y-m-d h-m-s'),
        'bookingDateEnd' => $end->format('Y-m-d h-m-s')
    ];
});
