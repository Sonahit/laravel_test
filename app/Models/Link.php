<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'userId',
        'bookingId',
        'deleteLink',
        'updateLink',
        'isActive',
        'expiresAt'
    ];

    public function user()
    {
        return $this->belongTo(User::class, 'userId', 'id');
    }
    public function booking()
    {
        return $this->hasOne(Booking::class, 'id', 'bookingId');
    }
}
