<?php

namespace App\Utils\Helpers; 


class DatabaseHelper{

  public const COLUMN_DOESNT_EXIST = "DOESNT EXIST";
  
  private const columns = [
    "flight_id" => "flight_load.flight_id",
    "flight_date" => "flight_load.flight_date",
    "plan_codes" => "flight_plan_prices.iata_code",
    "plan_qty" => "flight_plan_prices.meal_qty",
    "plan_price" => "flight_plan_prices.price",
    "fact_code" => "billed_meals.iata_code",
    "fact_qty" => "billed_meals.qty",
    "fact_price" => "billed_meals.total",
    'delta' => "flight_plan_prices.delta"
  ];

  public static function paramToColumn(string $param){
    $params = DatabaseHelper::columns;
    $key = array_key_exists($param, $params) ? $params[$param] : DatabaseHelper::COLUMN_DOESNT_EXIST;
    return $key;
  }
}
?>