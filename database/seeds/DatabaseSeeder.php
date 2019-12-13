<?php

use App\Models\Configuration;
use App\Models\Place;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!User::where('email', 'admin@admin.com')->exists()) {
            User::create([
                'firstName' => 'admin',
                'lastName' => 'admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin'), // password
                'remember_token' => Str::random(10),
                'isAdmin' => 1
            ]);
        }
        Configuration::create([
            'name' => 'REGISTRATION_IS_OPEN',
            'BOOL_VAL' => true
        ]);
        factory(Place::class, 3)->create();
    }
}
