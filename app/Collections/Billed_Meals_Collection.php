<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class Billed_Meals_Collection extends Collection{

  public function formatByDate(){
    return $this->map(function($items){
      return $items
          ->groupBy("flight_date")
          ->map(function($valuesByDate){
            return $valuesByDate->reduce(function($accum, $value){
                $fact = &$accum["fact_attributes"];
                if(is_null($accum["id"])) $accum["id"] = $value->flight_id;
                if(is_null($accum["date"])) $accum["date"] = $value->flight_date;
                if(is_null($accum["class"])) $accum["class"] = $value->class;
                if(is_null($accum["type"])) $accum["type"] = $value->type;                                
                $accum["plan_attributes"] = $value->new_matrix->groupAndSum();
                $billed_prices = $value->billed_meals_prices;
                $iata_code = $value->billed_meals_info->iata_code;
                $fact["qty"] += $billed_prices->qty;
                $fact["price"] += $billed_prices->qty * $billed_prices->price_per_one;
                if(!in_array($iata_code, $fact["codes"])) array_push($fact["codes"], $iata_code);
                return $accum;
              }, [
                "id" => null,
                "date" => null,
                "class" => null,
                "type" => null,
                "fact_attributes" => [
                  "qty" => 0,
                  "codes" => [],
                  "price" => 0 
                ],
                "plan_attributes" => [
                  "qty" => 0,
                  "codes" => [],
                  "price" => 0 
                ]
            ]);
          });
      });
    }
  }
?>