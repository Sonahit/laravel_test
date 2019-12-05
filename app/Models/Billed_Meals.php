<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billed_Meals extends Model
{
  protected $table = 'billed_meals';
  protected $primateKey = 'id';
  protected $perPage = 40;

  public const searchableRows = [
      "flight_id",
      "flight_date",
      "iata_code",
      "class",
      "type"
  ];

  public static function boot()
  {
    parent::boot();
    static::addGlobalScope('business', function($q){
      $q->where('class', 'Бизнес')
        ->where('type', 'Комплект');
    });
    static::addGlobalScope('noALC', function($q){
      $q->where('iata_code', '<>', "ALC");
    });
  }

  public function flight_load()
  {
    return $this->belongsTo(Flight_Load::class, 'flight_load_id', 'id');
  }
}
