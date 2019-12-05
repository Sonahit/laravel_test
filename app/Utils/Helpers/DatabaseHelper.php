<?php

namespace App\Utils\Helpers;

use Error;

class DatabaseHelper{

  public const COLUMN_DOESNT_EXIST = "DOESNT EXIST";
  
  private const columns = [
    "flight_id" => "flight_load.flight_id",
    "flight_date" => "flight_load.flight_date",
    "plan_codes" => "flight_plan_prices.iata_code",
    "plan_qty" => "flight_plan_prices.meal_qty",
    "plan_price" => "flight_plan_prices.price",
    "fact_codes" => "billed_meals.iata_code",
    "fact_qty" => "billed_meals.qty",
    "fact_price" => "billed_meals.total",
    'delta' => "flight_plan_prices.delta"
  ];

  public static function paramToColumn(string $param){
    $params = DatabaseHelper::columns;
    $key = array_key_exists($param, $params) ? $params[$param] : DatabaseHelper::COLUMN_DOESNT_EXIST;
    return $key;
  }

  public static function typeOfColumn(string $column)
  {
    if($column === 'meal_qty' || $column === 'total' || $column === 'delta' || $column === 'price' || $column === 'flight_id' || $column === 'qty')
    {
      return 'number';
    }
    if($column === 'flight_date')
    {
      return 'date';
    }
    return 'string';
  }

  public static function getModelInstance(string $tableName)
  {
    if($tableName === 'flight_load')
    {
      return new \App\Models\Flight_Load;
    } else if ($tableName === 'flight_plan_prices')
    {
      return new \App\Models\Flight_Plan_Prices;
    } else if ($tableName === 'billed_meals')
    {
      return new \App\Models\Billed_Meals;
    } else if ($tableName === 'flight')
    {
      return new \App\Models\Flight;
    }
    throw new Error("No model found");
  }
}
?>