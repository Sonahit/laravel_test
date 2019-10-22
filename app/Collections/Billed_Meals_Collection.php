<?php

namespace App\Collections;

use App;
use Illuminate\Support\Collection;

class Billed_Meals_Collection extends Collection{
  public function withNewMatrix(){
    foreach ($this as $billed_meal) {
      $billed_meal->withNewMatrix();
    };
    return $this;
  }

  public function flatCollection()
    {
        /**
         * collection 
         * -> collection_id 
         * -> collection_date 
         * -> (Sum Prices, Sum qty)
         */
        $billed_meals = new Billed_Meals_Collection();
        foreach ($this as $billed_meal) {
          $billed_prices = $billed_meal->billed_meals_prices;
          $billed_info = $billed_meal->billed_meals_info;
          $meal_rules = $billed_meal->meal_rules;
          $planned = [
            'codes' => array(),
            'qty' => 0,
            'price' => 0,
          ];
          $fact = [
            'codes' => $billed_info->iata_code,
            'qty' => $billed_prices->qty,
            'price' => $billed_prices->price_per_one * $billed_prices->qty * 1.04 * 1.18,
          ];
          if($meal_rules){
            $new_matrix_collection = $meal_rules->new_matrix;
            foreach ($new_matrix_collection as $nm) {
              $business_prices = $nm->meal_info->business_meal_prices;
              $planned['price'] += $business_prices->price * $nm->meal_qty;
              $planned['qty'] += $nm->meal_qty;
              if(!in_array($nm->iata_code, $planned['codes'], true)){
                array_push($planned['codes'],$nm->iata_code);
              }
            }
          }
          $billed_meals->add(
            [
              "id" => $billed_meal->flight_id,
              "date" => $billed_meal->flight_date,
              "name" => $billed_meal->name,
              "iata_code" => $billed_info->iata_code,
              "planned_attributes" => $planned,
              "fact_attributes" => $fact
            ]
          );
        }
        //TODO: Group by data
        return $billed_meals;
    }
  
}

?>