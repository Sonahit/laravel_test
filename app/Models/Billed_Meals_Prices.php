<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billed_Meals_Prices extends Model
{
    protected $table = 'billed_meals_prices';

    public function billed_meals()
    {
        return $this->hasMany('App\Models\Billed_Meals', 'delivery_number', 'delivery_number')
            ->where('name', $this->billed_meals_info()->name);
    }

    public function billed_meals_info()
    {
        return $this->hasOne('App\Models\Billed_Meals_Info', 'name', 'name');
    }
}
