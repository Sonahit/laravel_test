<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal_Info extends Model
{
    protected $table = "meal_info";

    public function business_meal_prices()
    {
        return $this->hasOne('App\Models\Business_Meal_Prices','nomenclature', 'nomenclature');
    }
}
