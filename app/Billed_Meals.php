<?php

namespace App;

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
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('january_business', function(Builder $b){
            $b->whereBetween('flight_date', ['20170101', '20170131']);
        });
    }

    public static function scopeSort($q){
        return $q->orderBy('flight_id', 'asc')->orderBy('flight_date', 'asc');
    }

    public function flight_load()
    {
        return $this->belongsTo('App\Flight_Load', 'flight_load_id', 'id');
    }

    public function billed_meals_info()
    {
        return $this->hasOne('App\Billed_Meals_Info', 'name', 'name');
    }

    public function billed_meals_price()
    {
        return $this->hasOne('App\Billed_Meals_Prices', 'delivery_number', 'delivery_number')
            ->where('name', $this->billed_meals_info()->name);
    }

    public function meal_rules()
    {
        return $this->hasOne('App\Meal_Rules', 'flight_id', 'flight_id')
            ->where("class", '=', 'Бизнес')
            ->where("iata_code", '=', "{$this->billed_meals_info->iata_code}")
            ->where("weeknumber", '=', DB::raw("WEEK('{$this->flight_date}') % 2"));
    }

    public function new_matrix()
    {
        return $this->hasManyThrough(
        'App\New_Matrix',
        'App\Meals_Price',
         'iata_code',
         'nomenclature',
         'iata_code',
         'nomenclature'
        )->where('`new_matrix`.`passenger_amount`', $this->flight_load->business);
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

    /**
     * @return \App\Billed_Meals  
     */
    public function getBilledMeals(String $rows, Integer $limit, Array $where){
        return Billed_Meals::select($rows)
        ->whereBetween('flight_date', [$this->from, $this->to])
        ->where($where)
        ->limit($limit)
        ->orderBy('flight_id', 'asc')
        ->orderBy('flight_date', 'asc')
        ->get();
    }

    public function getReport(Integer $limit, Integer $from, Integer $to){
        return ;
    }
}
