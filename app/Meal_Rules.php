<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal_Rules extends Model
{
    protected $table = 'meal_rules';
    protected $primaryKey = 'id';

    public function new_matrix(){
        return $this -> hasMany('App\New_Matrix');
    }
}
