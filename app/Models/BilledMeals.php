<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BilledMeals extends Model
{
    public const SEARCHABLE_ROWS = [
        'flight_id',
        'flight_date',
        'iata_code',
        'class',
        'type',
    ];
    protected $table = 'billed_meals';
    protected $primateKey = 'id';
    protected $perPage = 40;

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('business', function ($q) {
            $q->where('class', 'Бизнес')
                ->where('type', 'Комплект')
            ;
        });
        static::addGlobalScope('noALC', function ($q) {
            $q->where('iata_code', '<>', 'ALC');
        });
    }

    public function flightLoad()
    {
        return $this->belongsTo(FlightLoad::class, 'flight_load_id', 'id');
    }
}
