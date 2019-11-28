<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight_Plan_Prices extends Model
{
  protected $table = "flight_plan_prices";

  public function scopeJanuary($q){
        return $q->whereBetween('flight_plan_prices.flight_date', ['20170101', '20170131']);
  }

}
