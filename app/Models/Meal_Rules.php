<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal_Rules extends Model
{
    protected $table = 'meal_rules';
    protected $primaryKey = 'id';

    public function new_matrix(int $passenger_amount = 0){
        return $this
            ->hasMany('App\Models\New_Matrix','iata_code', 'iata_code')
            ->with('meal_info.business_meal_prices')
            ->where('passenger_amount', $passenger_amount);
    }
}
