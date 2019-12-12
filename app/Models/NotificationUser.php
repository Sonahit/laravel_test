<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    protected $table = 'notification_users';
    protected $fillable = ['userId', 'notificationId'];

    public function notification()
    {
        return $this->hasOne(Notification::class, 'id', 'notificationId');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userId');
    }
}
