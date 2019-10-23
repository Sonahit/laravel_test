<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class Billed_Meals_Collection extends Collection{

  public function withNewMatrix(){
    foreach ($this as $billed_meal) {
      $billed_meal->withNewMatrix();
    };
    return $this;
  }

  public function withPrices(){
    foreach ($this as $billed_meal) {
      $billed_meal->withPrices();
    };
    return $this;
  }

}
?>