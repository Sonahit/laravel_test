<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Collections\Flight_Load_Collection;
use Illuminate\Support\Facades\DB;

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

    public function billed_meals()
    {
        return $this->hasMany(Billed_Meals::class, 'flight_load_id', 'id');
    }

    public function scopeBusiness($q){
        return $q->select('id', 'flight_id', 'flight_date', 'business');
    }

    public function scopeJanuary($q){
        return $q->whereBetween('flight_date', ['20170101', '20170131']);
    }

    public static function scopeSort($q, $asc){
        return $q->orderBy('flight_id', $asc ? 'asc' : 'desc')
             ->orderBy('flight_date', $asc ? 'asc' : 'desc');
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
        )->join('flight_load', function ($join){
            $join
                ->on('flight_load.id', '=', 'billed_meals.flight_load_id')
                ->on('flight_load.business', '=', 'new_matrix.passenger_amount');
        })
        ->join('business_meal_prices', function ($join){
            $join->on('business_meal_prices.nomenclature', '=', 'new_matrix.nomenclature');
        });
    }

    public function flight()
    {
        return $this->hasOne(Flight::class, 'id', 'flight_id');
    }
}
