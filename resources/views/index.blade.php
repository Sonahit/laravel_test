<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>S7</title>
</head>
<body>
    {!! $links !!}
    <table>
      <thead>
        <tr>
          <th rowspan="2">Номер полёта</th>
          <th rowspan="2">Дата полёта</th>
          <th rowspan="2">Класс</th>
          <th rowspan="2">Название</th>
          <th rowspan="2">Тип номенклатуры</th>
          <th colspan="2">Код</th>
          <th colspan="2">Количество</th>
          <th colspan="2">Цена</th>
        </tr>
        <tr>
          <th>План</th>
          <th>Факт</th>

          <th>План</th>
          <th>Факт</th>

          <th>План</th>
          <th>Факт</th>
        </tr>
      </thead>
      <tbody> 
        @foreach (groupByData(flattenBilled($billed_meals_collect)) as $key => $billed_meal)
          <tr>
            <td>  {{ $billed_meal['id']}} </td>
            <td>  {{ $billed_meal['date'] }}</td>
            <td>  {{ $billed_meal['class'] }}</td>
            <td>  {{ $billed_meal['name'] }}</td>
            <td>  {{ $billed_meal['type'] }}</td>
            <td>  {{ implode(", ",$billed_meal['plan_attributes']['codes'])}}</td>
            <td>  {{ implode(", ",$billed_meal['fact_attributes']['codes'])}}</td>
            <td>  {{ $billed_meal['plan_attributes']['qty'] }}</td>
            <td>  {{ $billed_meal['fact_attributes']['qty'] }}</td>
            <td>  {{ $billed_meal['plan_attributes']['price']}}</td>
            <td>  {{ $billed_meal['fact_attributes']['price'] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
</body>
</html>

<?php
function flattenBilled($billed_meals_collection){
  $billed_meals = array();
  foreach ($billed_meals_collection as $billed_meal) {
    $billed_prices = $billed_meal->billed_meals_prices;
    $billed_info = $billed_meal->billed_meals_info;
    $meal_rules = $billed_meal->meal_rules;
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
    if($meal_rules){
      $new_matrix_collection = $meal_rules->new_matrix;
    } else if($billed_meal->new_matrix) {
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
        "name" => $billed_meal->name,
        "class" => $billed_meal->class,
        "type" => $billed_meal->type,
        "plan_attributes" => $planned,
        "fact_attributes" => $fact
      ]);
  };
  return $billed_meals;
}

function groupByData($billed_meals_collection){
  return $billed_meals_collection;
}

function push_if_not_exists(Array &$array, $element = null){
  if(!in_array($element, $array, true)){
    array_push($array,$element);
    return true;
  }
  return false;
}      
  ?>