<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    public const ROWS = ['id', 'city', 'address', 'timezone', 'startHours', 'endHours', 'bookingInterval'];
    protected $table = 'places';
}
