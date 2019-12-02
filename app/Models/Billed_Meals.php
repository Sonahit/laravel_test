<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billed_Meals extends Model
{
  protected $table = 'billed_meals';
  protected $primateKey = 'id';
  protected $perPage = 40;

  public const NO_LIMIT = -1;

  public const searchableRows = [
      "flight_id",
      "flight_date",
      "iata_code",
      "class",
      "type"
  ];

  public function scopeBusiness($q){
      return $q->where('class', 'Бизнес')
              ->where('type', 'Комплект');
  }

  public function scopeNoALC($q){
      return $q->where('iata_code', '<>', "ALC");
  }

  public function new_matrix_prices()
  {
    return $this->hasMany(New_Matrix_Prices::class, 'iata_code', 'iata_code');
  }

  public function flight_load(){
    return $this->belongsTo(Flight_Load::class, 'flight_load_id', 'id');
  }
}
