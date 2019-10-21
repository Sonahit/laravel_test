<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal_Info extends Model
{
    protected $table = "meal_info";

    public function new_matrix()
    {
        return $this->belongsToMany('App\New_Matrix', 'new_matrix', 'meal_id', 'meal_id');
    }
}
