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
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userId');
    }
    public function userInfo()
    {
        return $this->user();
    }
}
