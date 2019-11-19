<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class Billed_Meals_Collection extends Collection{

  public function formatByDate(){
    return $this->map(function($items){
      $new_collection = $items
          ->groupBy("flight_date")
          ->map(function($values_at_date){
              $accum = [];
              $accum["fact_attributes"] = [];
              $fact = &$accum["fact_attributes"];
              $fact["qty"] = 0;
              $fact["price"] = 0;
              $fact["codes"] = [];
              foreach ($values_at_date as $value) {
                  if(!array_key_exists("id", $accum)) $accum["id"] = $value->flight_id;
                  if(!array_key_exists("date", $accum)) $accum["date"] = $value->flight_date;
                  if(!array_key_exists("class", $accum)) $accum["class"] = $value->class;
                  if(!array_key_exists("type", $accum)) $accum["type"] = $value->type;                                
                  $accum["plan_attributes"] = $value->new_matrix->groupAndSum();
                  $billed_prices = $value->billed_meals_prices;
                  $iata_code = $value->billed_meals_info->iata_code;
                  $fact["qty"] += $billed_prices->qty;
                  $fact["price"] += $billed_prices->qty * $billed_prices->price_per_one;
                  if(!in_array($iata_code, $fact["codes"])) array_push($fact["codes"], $iata_code);
              }
              return $accum; 
          });
      return $new_collection;
    });
  }
  
}
?>