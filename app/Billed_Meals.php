<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billed_Meals extends Model
{
    #TODO DATA METHODS
    protected $table = 'billed_meals';
    protected $id;
    protected $flight_id;
    protected $flight_load_id;
    protected $flight_date;
    protected $iata_code;
    protected $type;
    protected $qty;
    protected $price_per_one;

}
