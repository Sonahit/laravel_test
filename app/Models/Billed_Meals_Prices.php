<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billed_Meals_Prices extends Model
{
    protected $table = 'billed_meals_prices';

    public function billed_meals()
    {
        return $this->belongsToMany('App\Models\Billed_Meals','billed_meals',
            'delivery_number',
            'name');
    }

    public function billed_meals_info()
    {
        return $this->hasOne('App\Models\Billed_Meals_Info', 'name', 'name');
    }
}
