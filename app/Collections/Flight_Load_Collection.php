<?php

namespace App\Collections;

use App\Utils\Helpers\DatabaseHelper;
use Illuminate\Database\Eloquent\Collection;

class Flight_Load_Collection extends Collection{

  public function groupBy($groupBy, $preserveKeys = false)
  {
    return new static(parent::groupBy($groupBy, $preserveKeys));
  }

  public function sortValues($column, $ascending = false, $options = SORT_REGULAR)
  {
    if($column === DatabaseHelper::COLUMN_DOESNT_EXIST || is_null($column)) return $this;
    return $this->sortBy($column, $options, !$ascending)
            ->values()
            ->all();
  }

  public function flatten($depth = INF)
  {
    return new static(parent::flatten($depth));
  }
  
  public function formatByDate()
  {
    return new static(
        $this->map(function($items)
        {
          return $items
            ->map(function($valuesByDate)
            {
              return $valuesByDate->reduce(function($accum, $value)
                {
                  $billed_meals = $value->billed_meals->first();
                  $flight_plan = $value->flight_plan_prices;
                  $accum["id"] = $value->flight_id;
                  $accum["date"] = $value->flight_date;
                  $accum["class"] = $billed_meals->class;
                  $accum["type"] = $billed_meals->type;      
                  if($flight_plan)              
                  {
                    $accum["plan_codes"] = explode(',', $flight_plan->iata_code);
                    $accum["plan_price"] = floatval($flight_plan->price);
                    $accum["plan_qty"] = floatval($flight_plan->meal_qty);
                  }
                  $accum["fact_codes"] = explode(',', $billed_meals->iata_code);
                  $accum["fact_qty"] = floatval($billed_meals->fact_qty);
                  $accum["fact_price"] = floatval($billed_meals->fact_price);
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
        })
    );
  }
}
?>