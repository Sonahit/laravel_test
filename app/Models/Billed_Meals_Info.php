<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billed_Meals_Info extends Model
{
    protected $table = 'billed_meals_info';

    public function billed_meals()
    {
        return $this->hasMany('App\Models\Billed_Meals', 'name', 'name');
    }

    public function billed_meals_prices()
    {
        return $this->belongsToMany('App\Models\Billed_Meals_Prices','billed_meals_prices', 'name', 'name');
    }

    public function meal_rules()
    {
        return $this->hasMany('App\Meal_Rules','iata_code', 'iata_code');
    }
}
