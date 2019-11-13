<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billed_Meals_Info extends Model
{
    protected $table = 'billed_meals_info';

    public function billed_meals()
    {
        return $this->hasMany(Billed_Meals::class, 'name', 'name');
    }

    public function billed_meals_prices()
    {
        return $this->belongsToMany(Billed_Meals_Prices::class,'billed_meals_prices', 'name', 'name');
    }

    public function meal_rules()
    {
        return $this->hasMany(Meal_Rules::class,'iata_code', 'iata_code');
    }
}
