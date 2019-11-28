<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Collections\Flight_Load_Collection;

class Flight_Load extends Model
{
    protected $table = 'flight_load';
    protected $primaryKey = 'id';
    protected $perPage = 40;

    public const searchableRows = [
        "flight_id",
        "flight_date"
    ];

    public function newCollection(array $models = [])
    {   
        return new Flight_Load_Collection($models);
    }

    public function scopeBusiness($q){
        return $q->select('id', 'flight_id', 'flight_date', 'business');
    }

    public function scopeJanuary($q){
        return $q->whereBetween('flight_date', ['20170101', '20170131']);
    }

    public function scopeSort($q, $asc){
        return $q
              ->orderBy('flight_id', $asc ? 'asc' : 'desc')
              ->orderBy('flight_date', $asc ? 'asc' : 'desc');
    }

    public function billed_meals()
    {
        return $this->hasMany(Billed_Meals::class, 'flight_load_id', 'id');
    }

    public function new_matrix()
    {
        return $this->hasManyThrough(
          New_Matrix::class,
          Billed_Meals::class,
          'flight_load_id',
          'iata_code',
          'id',
          'iata_code'
        )
        ->with('business_meal_prices');
    }
    
    public function new_matrix_prices(){
      return $this->hasManyThrough(
          New_Matrix_Prices::class,
          Billed_Meals::class,
          'flight_load_id',
          'iata_code',
          'id',
          'iata_code');
    }

    public function flight_plan_prices(){
      return $this->hasOneThrough(
        Flight_Plan_Prices::class,
        Billed_Meals::class,
        'flight_load_id',
        'billed_meals_id',
        'id',
        'id'
      );
    }

    public function flight()
    {
        return $this->hasOne(Flight::class, 'id', 'flight_id');
    }
}
