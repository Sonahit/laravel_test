<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
