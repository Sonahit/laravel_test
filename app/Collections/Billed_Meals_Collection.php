<?php

namespace App\Collections;

use App;
use Illuminate\Database\Eloquent\Collection;

class Billed_Meals_Collection extends Collection{
  public function withNew_Matrix(){
    foreach ($this as $bm => $billed_meal) {
      // TODO: 
      //https://stackoverflow.com/questions/56993427/how-to-get-eloquent-relationship-using-parent-pivot-value-as-where-condition
      if($billed_meal->billed_meals_info){
        $billed_meal->meal_rules;
        $billed_meal->billed_meals_prices;
        $billed_meal->new_matrix;
      }
    };
  }
  
}


?>