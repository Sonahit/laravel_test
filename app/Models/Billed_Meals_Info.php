<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billed_Meals_Info extends Model
{
    protected $table = 'billed_meals_info';

    public function billed_meals()
    {
        return $this->hasMany('App\Billed_Meals', 'name', 'name');
    }

    public function billed_meals_info()
    {
        return $this->belongsToMany('App\Billed_Meals_Prices','billed_meals_prices', 'name', 'name');
    }
}
