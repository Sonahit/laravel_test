<?php

namespace App\Models;

use App\Collections\Billed_Meals_Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Billed_Meals extends Model
{
    protected $table = 'billed_meals';
    protected $perPage = 40;

    public const NO_LIMIT = -1;

    public const searchableRows = [
        "flight_id",
        "flight_date",
        "iata_code",
        "qty",
        "total"
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function scopeBusiness($q){
        return $q->where('class', 'Бизнес')
                ->where('type', 'Комплект');
    }

    public function newCollection(array $models = [])
    {   
        return new Billed_Meals_Collection($models);
    }

    public function scopeNoALC($q){
        return $q->where('iata_code', '<>', "ALC");
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
        return $this->belongsToMany(
            New_Matrix::class,
            'billed_meals',
            'id',
            'iata_code',
            'ids',
            'iata_code'
        )->select(
            "new_matrix.iata_code",
            DB::raw("SUM(new_matrix.meal_qty) as plan_qty"),
            "new_matrix.passenger_amount",
            "new_matrix.nomenclature",
            DB::raw("SUM(business_meal_prices.price * new_matrix.meal_qty) as plan_price")
        )
        ->join('flight_load', function ($join){
            $join
                ->on('flight_load.id', '=', 'billed_meals.flight_load_id')
                ->on('flight_load.business', '=', 'new_matrix.passenger_amount');
        })
        ->join('business_meal_prices', function ($join){
            $join->on('business_meal_prices.nomenclature', '=', 'new_matrix.nomenclature');
        })
        ->groupBy("billed_meals.id");
    }
}
