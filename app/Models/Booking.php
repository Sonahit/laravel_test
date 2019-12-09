<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'userId', 'placeId', 'bookingDateStart', 'bookingDateEnd'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function places()
    {
        return $this->hasMany(Place::class, 'id', 'placeId');
    }

    public function scopeBookedBetween(Builder $q, Carbon $start, Carbon $end)
    {
        return $q->whereBetween('bookingDateStart', [$start, $end])
                ->whereTime('bookingDateStart', '>=', DB::raw("'{$start->toTimeString()}'"))
                ->whereTime('bookingDateEnd', '<=',  DB::raw("'{$end->toTimeString()}'"));
    }
}
