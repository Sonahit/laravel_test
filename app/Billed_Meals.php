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

    public $from = '20170101';
    public $to = '20170131';
    public const NO_LIMIT = -1;
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('january_business', function(Builder $b){
            $b->whereBetween('flight_date', ['20170101', '20170131'])
            ->where([
                ['type', '=','Комплект'],
                ['class', '=','Бизнес'],
                ['iata_code', '<>', 'ALC']
            ]);
        });
    }
    //#TODO RELATIONSHIPS
    public function flight_load()
    {
        return $this->hasOne('App\Flight_Load', 'id', 'flight_load_id');
    }

    public function meal_rules()
    {
        return $this->hasOneThrough(
            'App\Meal_Rules',
            'App\Billed_Meals',
            'flight_date', // out from Billed_Meals
            'iata_code', // out from Meal_Rules 
            'flight_date', // local to Billed_Meals
            'iata_code' // local to Meal_Rules 
        )
        ->where(DB::raw('WEEK(`billed_meals`.`flight_date`) % 2'), '=', DB::raw('`meal_rules`.weeknumber'))
        ->groupby(DB::raw('meal_rules.iata_code'));
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
