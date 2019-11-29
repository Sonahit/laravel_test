<?php

namespace App\Models;

use App\Utils\Helpers\DatabaseHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Flight_Plan_Prices extends Model
{
  protected $table = "flight_plan_prices";

  public function scopeJanuary($q){
        return $q->whereBetween('flight_plan_prices.flight_date', ['20170101', '20170131']);
  }

}
