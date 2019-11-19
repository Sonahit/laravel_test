<?php

namespace App\Models;

use App\Collections\Billed_Meals_Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Billed_Meals extends Model
{
    protected $table = 'billed_meals';
    protected $perPage = 20;

    public const NO_LIMIT = -1;

    public static function scopeJanuaryBusiness($q){
        return $q 
            ->whereBetween('flight_date', ['20170101', '20170131'])
            ->where('class', 'Бизнес')
            ->where('type', 'Комплект');
    }

    public function newCollection(array $models = [])
    {   
        return new Billed_Meals_Collection($models);
    }

    public static function selectDefault($q){
        return $q ->select(
            'id',
            'flight_id',
            'flight_date',
            'flight_load_id',
            "name",
            'delivery_number',
            'class',
            'type'
        );
    }
    public static function scopeSort($q, $asc){
       return Billed_Meals::selectDefault($q)
                            ->orderBy('flight_id', $asc ? 'asc' : 'desc')
                            ->orderBy('flight_date', $asc ? 'asc' : 'desc');
    }

    public function flight_load()
    {
        return $this->hasOne(Flight_Load::class, 'id', 'flight_load_id');
    }

    public function billed_meals_info()
    {
        return $this->hasOne(Billed_Meals_Info::class, 'name', 'name');
    }

    public function billed_meals_prices()
    {
        return $this->hasOne(Billed_Meals_Prices::class, 'billed_meals_id');
    }

    public function new_matrix(){
        return $this->hasManyThrough(
            New_Matrix::class,
            Billed_Meals::class,
            'id',
            'iata_code',
            'id',
            'iata_code');
    }
}
