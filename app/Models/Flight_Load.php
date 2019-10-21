<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight_Load extends Model
{
    protected $table = 'flight_load';
    protected $primaryKey = 'id';

    public function billed_meals()
    {
        return $this->hasMany('App\Billed_Meals', 'flight_load_id', 'id');
    }
}
