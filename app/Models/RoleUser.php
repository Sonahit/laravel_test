<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';

    public function roles()
    {
        return $this->belongsTo(Role::class, 'roleId', 'id');
    }

    public function user()
    {
        return $this->belongTo(User::class, 'userId', 'id');
    }
}
