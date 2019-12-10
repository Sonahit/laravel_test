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
        return $q->whereBetween('bookingDateStart', [$start, $end])
            ->whereTime('bookingDateStart', '>=', DB::raw("'{$start->toTimeString()}'"))
            ->whereTime('bookingDateEnd', '<=', DB::raw("'{$end->toTimeString()}'"));
    }
}
