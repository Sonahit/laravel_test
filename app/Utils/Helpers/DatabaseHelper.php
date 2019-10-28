<?php

namespace App\Utils\Helpers; 

use Illuminate\Support\Facades\DB;

class DatabaseHelper{

  public static function updateOrInsert($tableName, $columns, $rows){
    foreach($columns as $column){
      $insert = [];
      foreach ($rows as $row) {
        $insert[$row] = $column->$row;
      }
      //TODO: Test me
      //if(!DB::table($tableName)->whereExists($insert)){
        DB::table($tableName)->updateOrInsert($insert);
      //}
    }
  }

  public static function insert($tableName, $columns, $rows){
    foreach($columns as $column){
      $insert = [];
      foreach ($rows as $row) {
        $insert[$row] = $column->$row;
      }
      DB::table($tableName)->insert($insert);
    }
  }

public static function flattenBilled($billed_meals_collection){
  $billed_meals = array();
  foreach ($billed_meals_collection as $billed_meal) {
    $billed_prices = $billed_meal->billed_meals_prices;
    $billed_info = $billed_meal->billed_meals_info;
    $planned = [
      'codes' => array(),
      'qty' => 0,
      'price' => 0,
    ];
    $fact = [
      'codes' => array($billed_info->iata_code),
      'qty' => $billed_prices->qty,
      'price' => $billed_prices->price_per_one * $billed_prices->qty * 1.04 * 1.18,
    ];
    if($billed_meal->new_matrix) {
      $new_matrix_collection = $billed_meal->new_matrix;
    } else {
      $new_matrix_collection = array();
    };
    foreach ($new_matrix_collection as $nm) {
        $business_prices = $nm->meal_info->business_meal_prices;
        $planned['price'] += $business_prices->price * $nm->meal_qty;
        $planned['qty'] += $nm->meal_qty;
        push_if_not_exists($planned['codes'], $nm->iata_code);
      }
    array_push($billed_meals,
      [
        "id" => $billed_meal->flight_id,
        "date" => $billed_meal->flight_date,
        "class" => $billed_meal->class,
        "type" => $billed_meal->type,
        "plan_attributes" => $planned,
        "fact_attributes" => $fact
      ]);
  };
  return $billed_meals;
}
public static function groupByKey($billed_meals_collection, $do_group, $key){
  if(!$do_group) return $billed_meals_collection;
  $grouped_collection = $billed_meals_collection;
  //Should be i of elements with key occures more than 1
  $sameDate = findBySameKey($grouped_collection, $key);
  //Grouping
  foreach ($sameDate as $id => $data_duplicates) {
    foreach ($data_duplicates as $date => $indexes) {
      $start = $sameDate[$id][$date][0];
      $plan_group = [
        'codes' => array(),
        'qty' => 0,
        'price' => 0,
      ];
      $fact_group = [
        'codes' => array(),
        'qty' => 0,
        'price' => 0,
      ];
      foreach ($indexes as $i) {
        $el = $grouped_collection[$i];
        //Fact group
        $fact = $el['fact_attributes'];
        $plan = $el['plan_attributes'];
        $code = $fact['codes'][0];
        if(!in_array($code, $fact_group['codes'], true)){
          array_push($fact_group['codes'], $code);
        }
        $fact_group['qty'] += $fact['qty'];
        $fact_group['price'] += round($fact['price'], 2);
        //Plan group  
        $plan_group['qty'] = $plan['qty'];
        $plan_group['price'] = round($plan['price'], 2);
        if($i !== $start) $grouped_collection[$i] = [];
      }
      $grouped_collection[$start]['fact_attributes'] = $fact_group;
      $grouped_collection[$start]['plan_attributes'] = $plan_group;
    }
  }
  // return $billed_meals_collection;
  return $grouped_collection;
}

}

function findBySameKey(array $array, string $key){
  $same = [];
  for ($i = 0; $i < count($array); $i++) {
    $element = $array[$i];
    $key_s = $element[$key];
    $id = $element['id'];
    for ($j = $i + 1; $j < count($array); $j++) {
      $checkEl = $array[$j];
      if($id === $checkEl['id']){
        if($checkEl[$key] === $key_s){
          if(!array_key_exists($id, $same)){
            $same[$id] = [];
          }
          if(!array_key_exists($key_s, $same[$id])){
            $same[$id][$key_s] = [$i];
          }
          if(!in_array($j, $same[$id][$key_s], true)){
            array_push($same[$id][$key_s], $j);
          }
        }
      }
    }
  }
  return $same;
}

function push_if_not_exists(Array &$array, $element = null){
  if(!in_array($element, $array, true)){
    array_push($array,$element);
    return true;
  }
  return false;
}

?>