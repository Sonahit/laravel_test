<?php

namespace App\Models;

use App\Collections\Billed_Meals_Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Billed_Meals extends Model
{
    #TODO DATA METHODS
    protected $table = 'billed_meals';
    protected $primaryKey = 'id';
    protected $perPage = 10;
    
    public $from = '20170101';
    public $to = '20170131';
    public const NO_LIMIT = -1;
    /**
     * id
     * flight_id
     * flight_load_id
     * flight_date
     * name
     * delivery_code
     * class
     * type
     * airport
     * invoice
     */

    public static function scopeJanuaryBusiness($q){
        return $q 
            -> whereBetween('flight_date', ['20170101', '20170131'])
            ->where('class', 'Бизнес')
            ->where('type', 'Комплект');
    }

    public function newCollection(array $models = [])
    {   
        return new Billed_Meals_Collection($models);
    }

    public static function scopeSort($q){
        return $q
                ->select(
                    'flight_id',
                    'flight_date',
                    'flight_load_id',
                    'name',
                    'delivery_number',
                    'class',
                    'type'
                )->orderBy('flight_id', 'asc')
                ->orderBy('flight_date', 'asc');
    }

    //TODO: No alc scope

    public function flight_load()
    {
        return $this->hasOne('App\Models\Flight_Load', 'id', 'flight_load_id');
    }

    public function billed_meals_info()
    {
        return $this->belongsTo('App\Models\Billed_Meals_Info', 'name', 'name');
    }

    public function billed_meals_prices()
    {
        return $this->hasOneThrough(
            'App\Models\Billed_Meals_Prices',
            'App\Models\Billed_Meals',
            'name',
            'delivery_number',
            'name',
            'delivery_number');
    }

    public function meal_rules()
    {
        return $this->hasOne('App\Models\Meal_Rules', 'flight_id', 'flight_id')
            ->where("iata_code", '=', "{$this->billed_meals_info->iata_code}")
            ->where("weeknumber", '=', DB::raw("WEEK('{$this->flight_date}') % 2"));
    }

    public function withNewMatrix()
    {
        if($mr = $this->meal_rules){
            $business = $this->flight_load->business;
            $nm = $mr->new_matrix()->where('passenger_amount', $business)->get();
            $nm->withBusinessPrices();
            $mr->setRelation('new_matrix',$nm);
        }
        return $this;
    }



    /*
    
    <!-- @foreach ($billed_meals as $billed_meal)
          <tr>
            <td>{{ $billed_meal['flight_id']}}</td>
            <td>{{ $billed_meal['flight_date']}}</td>
            <td>{{ $billed_meal['type']}}</td>
            <td>{{ $billed_meal['class']}}</td> 
            <td>{{ $billed_meal['code.fact']}}</td> 
            <td>{{ $billed_meal['qty.fact']}}</td> 
            <td>{{ $billed_meal['price.fact']}}</td>
          </tr> 
        @endforeach
        -->
    */
}
