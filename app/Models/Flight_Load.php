<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Collections\Flight_Load_Collection;
use App\Utils\Helpers\DatabaseHelper;
use Illuminate\Support\Facades\Schema;

class Flight_Load extends Model
{
    protected $table = 'flight_load';
    protected $primaryKey = 'id';
    protected $perPage = 40;

    public const searchableRows = [
        "flight_load.flight_id",
        "flight_load.flight_date"
    ];

    public function newCollection(array $models = [])
    {   
      return new Flight_Load_Collection($models);
    }

    public function scopeBusiness($q)
    {
      return $q->select('flight_load.id as id', 'flight_load.flight_id as flight_id', 'flight_load.flight_date as flight_date', 'business');
    }

    public function scopeSortBy($q, array $attributes = null, bool $desc = true)
    {
      if(is_null($attributes) || $attributes[0] === DatabaseHelper::COLUMN_DOESNT_EXIST) return $q;
      $selfTable = $this->getTable();
      foreach($attributes as $attribute){
        [$tableName, $column] = explode('.', $attribute);
        if($selfTable === $tableName){
          $q->orderBy($column, $desc ? 'desc' : 'asc' );
          continue;
        }
        $q->orderBy(
          DatabaseHelper::getModelInstance($tableName)
            ->select($column)
            ->where(function($q) use ($tableName, $selfTable){
              if(Schema::hasColumn($tableName, 'flight_load_id'))
              {
                return $q->where("{$tableName}.flight_load_id", "{$selfTable}.id");
              }
              if(Schema::hasColumns($tableName, ['flight_date', 'flight_id']))
              {
                $q->where("{$tableName}.flight_date", "{$selfTable}.flight_date")
                  ->where("{$tableName}.flight_id", "{$selfTable}.flight_id");
              }
              return $q;
            })
            ->orderBy("{$tableName}.{$column}", $desc ? 'desc' : 'asc')
            ->limit(1)
        );
      }
      return $q;
    }

    public function scopeJanuary($q)
    {
      return $q->whereBetween('flight_load.flight_date', ['20170101', '20170131']);
    }

    public function billed_meals()
    {
      return $this->hasMany(Billed_Meals::class, 'flight_load_id', 'id');
    }

    public function flight_plan_prices()
    {
      return $this->hasOneThrough(
        Flight_Plan_Prices::class,
        Billed_Meals::class,
        'flight_load_id',
        'billed_meals_id',
        'id',
        'id'
      );
    }

    public function flight()
    {
      return $this->hasOne(Flight::class, 'id', 'flight_id');
    }
}
