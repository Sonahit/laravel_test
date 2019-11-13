<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business_Meal_Prices extends Model
{
    protected $table = 'business_meal_prices';

    public function new_matrix()
    {
        return $this->belongsTo(New_Matrix::class, 'nomenclature', 'nomenclature');
    }
}
