<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class Flight_Load_Collection extends Collection{

  public function groupBy($groupBy, $preserveKeys = false)
  {

    $grouped = new Flight_Load_Collection(parent::groupBy($groupBy, $preserveKeys));
    return $grouped;
  }
  
  public function formatByDate(){
    return $this->map(function($items){
      return $items
          ->map(function($valuesByDate){
            return $valuesByDate->reduce(function($accum, $value){
                $billed_meals = $value->billed_meals->first();
                $new_matrix = $value->new_matrix->first();
                $accum["id"] = $value->flight_id;
                $accum["date"] = $value->flight_date;
                $accum["class"] = $billed_meals->class;
                $accum["type"] = $billed_meals->type;      
                if($new_matrix)                  {
                  $accum["plan_codes"] = [explode(',', $new_matrix->iata_code)];
                  $accum["plan_price"] = $new_matrix->plan_price;
                  $accum["plan_qty"] = $new_matrix->plan_qty;
                }
                $accum["fact_codes"] = [explode(',', $billed_meals->fact_codes)];
                $accum["fact_qty"] = $billed_meals->fact_qty;
                $accum["fact_price"] = $billed_meals->fact_price;
                $accum["delta"] = $accum["plan_price"] - $accum["fact_price"];
                return $accum;
              }, [
                "id" => null,
                "date" => null,
                "class" => null,
                "type" => null,
                "fact_qty" => 0,
                "fact_codes" => [],
                "fact_price" => 0,
                "plan_qty" => 0,
                "plan_codes" => [],
                "plan_price" => 0,
                "delta" => 0
            ]);
          });
      });
    }
  }
?>