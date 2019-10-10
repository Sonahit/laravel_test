<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billed_Meals extends Model
{
    #TODO DATA METHODS
    protected $table = 'billed_meals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'flight_id', 'flight_date', 'iata_code', 'type', 'class', 'qty', 'price_per_one'
    ];

}
