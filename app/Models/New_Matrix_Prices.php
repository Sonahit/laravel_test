<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class New_Matrix_Prices extends Model
{
    protected $table = 'new_matrix_prices';

    public function flight_load(){
      return $this->hasOneThrough(
        Flight_Load::class, 
        Billed_Meals::class,
        'iata_code',
        'id',
        'iata_code',
        'flight_load_id');
    }
    public function billed_meals()
    {
      return $this->belongsTo(Billed_Meals::class, 'iata_code', 'iata_code')->noAlc()->business();
    }
}
