<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight_Load extends Model
{
    protected $table = 'flight_load';
    protected $primaryKey = 'id';

    public function billed_meals()
    {
        return $this->hasMany('App\Models\Billed_Meals', 'flight_load_id', 'id');
    }

    public function meal_rules()
    {
        $this->hasMany('App\Meal_Rules',DB::raw("IF(WEEK('{flight_date}') % 2 = 0, 1, 2)",'weeknumber'));
    }
}
