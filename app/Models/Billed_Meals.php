<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


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
        "qty",
        "total"
    ];

    public function scopeBusiness($q){
        return $q->where('class', 'Бизнес')
                ->where('type', 'Комплект');
    }

    public function scopeNoALC($q){
        return $q->where('iata_code', '<>', "ALC");
    }
}
