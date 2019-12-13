<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guard = 'web';
    protected $table = 'users';

    public const ROWS = ['id', 'firstName', 'lastName', 'email'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'userId', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'userId', 'id');
    }

    public function roleUser()
    {
        return $this->hasMany(RoleUser::class, 'userId', 'id');
    }

    public function links()
    {
        return $this->hasMany(Link::class, 'userId', 'id');
    }
    public function usersToNotify()
    {
        return $this->hasOne(UserToNotify::class, 'userId', 'id');
    }

    public function fullName()
    {
        return "{$this->firstName} {$this->lastName}";
    }
}
