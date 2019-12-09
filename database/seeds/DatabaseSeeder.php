<?php

use App\Models\Admin;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Place;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
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
        $roles = factory(Role::class, 5)->create();
        User::create([
            'firstName' => 'admin',
            'lastName' => 'admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'), // password
            'remember_token' => Str::random(10)
        ]);
        factory(Place::class, 3)->create();
        factory(User::class, 10)->create()
            ->each(function ($user) use($roles) {
                $place = Place::all()->random(1)->first();
                $booking = factory(Booking::class, 5)->make(['placeId' => $place->id]);
                $user->bookings()->saveMany($booking);
                $userRoles = factory(RoleUser::class, 2)->make([
                    'roleId' => $roles->random(1)->first()->id,
                    'userId' => $user->id
                ]);
                $user->roleUser()->saveMany($userRoles);
                $notifications = factory(Notification::class, 10)->make();
                $user->notifications()->saveMany($notifications);
            });
    }
}
