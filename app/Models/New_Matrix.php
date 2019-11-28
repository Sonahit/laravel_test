<?php

namespace App\Models;

use App\Business_Meal_Prices;
use App\Collections\New_Matrix_Collection;
use Illuminate\Database\Eloquent\Model;

class New_Matrix extends Model
{
    protected $table = 'new_matrix';
    protected $primaryKey = 'id';
    protected $perPage = 40;

    public function newCollection(array $models = [])
    {   
        return new New_Matrix_Collection($models);
    }

    public function flight_load(){
      $this->hasOneThrough(
        Flight_Load::class, 
        Billed_Meals::class,
        'iata_code',
        'id',
        'iata_code',
        'flight_load_id');
    }
    
    public function business_meal_prices(){
      $this->hasOne(Business_Meal_Prices::class, 'nomenclature', 'nomenclature');
    }
}
