<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class New_Matrix_Collection extends Collection{
  
  public function groupAndSum(){
      $no_values = [
        "codes" => [],
        "qty" => 0,
        "price" => 0
      ];
      $nm = $this->first();
      if(!$nm){
         return $no_values;
      }
      $code = $nm->iata_code;
      $qty = $this->sum("meal_qty");
      $overall_price = $this->reduce(function($acc, $item){
        return $acc + $item->business_meal_prices->price * $item->meal_qty;
      });
      $new_matrix = [
        "codes" => [$code],
        "qty" => $qty,
        "price" => $overall_price
      ];
      return $new_matrix;
  }
  
}


?>