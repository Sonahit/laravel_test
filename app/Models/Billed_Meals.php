<?php

namespace App\Models;

use App\Collections\Billed_Meals_Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Billed_Meals extends Model
{
    protected $table = 'billed_meals';
    protected $primaryKey = 'id';
    protected $perPage = 10;

    public const NO_LIMIT = -1;

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
        //Doesnt work because of seeding
        // $this->belongsTo(
        //     'App\Models\Billed_Meals_Prices',
        //     'delivery_number',
        //     'delivery_number')
        //         ->where('name', $this->name);
        return DB::table($this->getTable())->select(['name', 'delivery_number', 'qty', 'price_per_one'])
                    ->where('name', $this->name)
                    ->where('delivery_number', $this->delivery_number);
    }

    public function meal_rules()
    {
        return $this->hasOne('App\Models\Meal_Rules', 'flight_id', 'flight_id')
            ->where("iata_code", "{$this->billed_meals_info->iata_code}")
            ->where("weeknumber", DB::raw("IF(WEEK('{$this->flight_date}') % 2 = 0, 1, 2)"));
    }

    public function withPrices()
    {
        if($this->name){
            //$this->billed_meals_prices;
            $bmp = $this->billed_meals_prices()->get()->toArray();
            $this->setRelation('billed_meals_prices', $bmp[0]);
        }
        return $this;
    }

    public function withNewMatrix()
    {
        if($this->flight_load){
            $mr = $this->meal_rules;
            if ($mr){
                $business = $this->flight_load->business;
                $nm = $mr->new_matrix($business)->get();
                $mr->setRelation('new_matrix', $nm);
            } else {
                $this->new_matrix;
            }
        }
        return $this;
    }

    public function new_matrix(){
        return $this->belongsToMany(
            'App\Models\New_Matrix',
            'App\Models\Billed_Meals_Info',
            'name',
            'iata_code',
            'name',
            'iata_code')
        ->with('meal_info.business_meal_prices')
        ->where('passenger_amount', $this->flight_load->business);
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
