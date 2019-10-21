<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class New_Matrix extends Model
{
    protected $table = 'new_matrix';
    protected $primaryKey = 'id';

    public function meal_rules()
    {
        return $this->belongsTo('App\Meal_Rules', 'iata_code', 'iata_code');
    }

    public function flight_load(){
        return $this->belongsTo('App\Flight_Load','business', 'passenger_amount');
    }

    public function businness_meal_prices(){
        return $this->hasOne('App\Business_Meal_Prices', 'nomenclature', 'nomenclature');
    }
}
