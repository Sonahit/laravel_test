<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    protected $table = 'bookings';

    public const ROWS = ['id', 'userId', 'placeId', 'bookingDateStart', 'bookingDateEnd'];
    protected $fillable = [
        'userId', 'placeId', 'bookingDateStart', 'bookingDateEnd',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($booking) {
            $booking->links()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'placeId', 'id');
    }

    public function scopeBookedBetween(Builder $q, Carbon $start, Carbon $end)
    {
        return $q->whereBetween('bookingDateStart', [$start->toDateString(), $end->toDateString()])
            ->whereTime('bookingDateStart', '>=', $start->toTimeString())
            ->whereTime('bookingDateEnd', '<=', $end->toTimeString());
    }

    public function links()
    {
        return $this->hasOne(Link::class, 'bookingId', 'id');
    }
}
