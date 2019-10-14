<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight_Load extends Model
{
    protected $table = 'flight_load';
    protected $primaryKey = 'id';

    public function billed_meals()
    {
        return $this -> hasMany('App\Billed_Meals', 'flight_id', 'flight_id')
        ->where(
            [
                ['billed_meals.type', '=','Комплект'],
                ['billed_meals.class', '=','Бизнес']
            ]
        )
        ->orderBy('billed_meals.flight_id', 'asc')
        ->orderBy('billed_meals.flight_date', 'asc');
    }
}
