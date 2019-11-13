<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight_Load extends Model
{
    protected $table = 'flight_load';
    protected $primaryKey = 'id';

    public function billed_meals()
    {
        return $this->hasMany(Billed_Meals::class, 'flight_load_id', 'id');
    }

    public function meal_rules()
    {
        $this->hasMany(Meal_Rules::class,DB::raw("IF(WEEK('{flight_date}') % 2 = 0, 1, 2)",'weeknumber'));
    }
}
