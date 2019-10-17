<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business_Meal_Prices extends Model
{
    protected $table = 'business_meal_prices';

    public function new_matrix()
    {
        return $this->belongsTo('App\New_Matrix', 'nomenclature', 'nomenclature');
    }
}
