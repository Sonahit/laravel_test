<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Place::class, 3)->create();
        factory(\App\Models\User::class, 10)->create()
        ->each(function ($user) {
            $place = App\Models\Place::all()->random(1)->first();
            $booking = factory(\App\Models\Booking::class, 5)->make(['placeId' => $place->id]);
            $user->bookings()->saveMany($booking);
            $roles = factory(\App\Models\Role::class, 2)->make();
            $user->roles()->saveMany($roles);
            $notifications = factory(\App\Models\Notification::class, 10)->make();
            $user->notifications()->saveMany($notifications);
        });
    }
}
