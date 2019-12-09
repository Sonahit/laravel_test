<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

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

    public function isAdmin()
    {
        return $this->roleUser()->whereHas('roles', function($q){
            $q->where('name', config('auth.admin_role'));
        });
    }
}
