<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Booking;
use Faker\Generator as Faker;

$factory->define(Booking::class, function (Faker $faker) {
    $start = $faker->unique()->dateTimeBetween('-15 days', 'now');
    $end = $faker->unique()->dateTimeBetween('now', '+15 days');
    return [
        'bookingDateStart' => $start->format('Y-m-d h-m-s'),
        'bookingDateEnd' => $end->format('Y-m-d h-m-s')
    ];
});
