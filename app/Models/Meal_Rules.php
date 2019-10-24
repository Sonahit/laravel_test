<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Meal_Rules extends Model
{
    protected $table = 'meal_rules';
    protected $primaryKey = 'id';

    public function new_matrix(){
        return $this
            ->hasMany('App\Models\New_Matrix','iata_code', 'iata_code')
            ->with('meal_info.business_meal_prices');
    }

    public function billed_meals()
    {
        $this->belongsToMany('App\Billed_Meals', 'billed_meals','flight_id', 'flight_id');
    }

    public function billed_meals_info()
    {
        $this->belongsToMany('App\Billed_Meals_Info', 'billed_meals_info', 'iata_code', 'iata_code');
    }

    public function flight_load()
    {
        $this->belongsToMany('App\Flight_Load','flight_load', 'weeknumber', DB::raw("IF(WEEK('{flight_date}') % 2 = 0, 1, 2)"));
    }
}
