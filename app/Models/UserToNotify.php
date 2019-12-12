<?php

namespace App\Models;

use App\Mail\BookingCreated;
use App\Mail\BookingDeleted;
use App\Mail\BookingUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class UserToNotify extends Model
{
    protected $table = 'users_to_notify';
    protected $fillable = ['userId'];


    public static function created(Booking $bookingId)
    {
        $users = self::all();

        $users->each(function ($user) {
            Mail::to($user)->send(new BookingCreated($booking));
        });
        return self;
    }

    public static function updated(Booking $booking)
    {
        $users = self::all();

        $users->each(function ($user) {
            Mail::to($user)->send(new BookingUpdated($booking));
        });
        return self;
    }

    public static function deleted(Booking $booking)
    {
        $users = self::all();

        $users->each(function ($user) {
            Mail::to($user)->send(new BookingDeleted($booking));
        });
        return self;
    }
}
