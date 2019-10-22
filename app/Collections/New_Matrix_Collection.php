<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class New_Matrix_Collection extends Collection{
  public function withBusinessPrices(){
    foreach ($this as $new_matrix) {
      $new_matrix->withBusinessPrices();
    };
    return $this;
  }
  
}


?>