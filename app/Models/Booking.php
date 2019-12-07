<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    public const START = 14;
    public const END = 20;
    
    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function scopeBookedBetween(Builder $q, Carbon $start, Carbon $end)
    {
        return $q->whereBetween('bookingDateStart', [$start, $end])
                ->whereTime('bookingDateStart', '>=',  $start->toTimeString())
                ->whereTime('bookingDateEnd', '<=',  $end->toTimeString());
    }
}
