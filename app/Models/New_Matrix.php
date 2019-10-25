<?php

namespace App\Models;

use App\Collections\New_Matrix_Collection;
use Illuminate\Database\Eloquent\Model;

class New_Matrix extends Model
{
    protected $table = 'new_matrix';
    protected $primaryKey = 'id';

    public function newCollection(array $models = [])
    {   
        return new New_Matrix_Collection($models);
    }

    public function meal_info()
    {
        return $this->hasOne('App\Models\Meal_Info','meal_id','meal_id');
    }
}
