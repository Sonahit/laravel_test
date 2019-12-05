<?php

namespace App\Utils\Helpers;

use Error;

class DatabaseHelper
{
    public const COLUMN_DOESNT_EXIST = 'DOESNT EXIST';

    private const COLUMNS = [
        'flight_id' => 'flight_load.flight_id',
        'flight_date' => 'flight_load.flight_date',
        'plan_codes' => 'flight_plan_prices.iata_code',
        'plan_qty' => 'flight_plan_prices.meal_qty',
        'plan_price' => 'flight_plan_prices.price',
        'fact_codes' => 'billed_meals.iata_code',
        'fact_qty' => 'billed_meals.qty',
        'fact_price' => 'billed_meals.total',
        'delta' => 'flight_plan_prices.delta',
    ];

    public static function paramToColumn(string $param)
    {
        $params = DatabaseHelper::COLUMNS;

        return array_key_exists($param, $params) ? $params[$param] : DatabaseHelper::COLUMN_DOESNT_EXIST;
    }

    /**
     * Get model istance by its table name.
     *
     * @param string $tableName
     *
     * @throws Error If no model found
     * @return \App\Models\BilledMeals|\App\Models\Flight|\App\Models\FlightLoad|\App\Models\FlightPlanPrices
     */
    public static function getModelInstance(string $tableName)
    {
        foreach (scandir(app_path('./Models')) as $modelName) {
            if (!('.' == $modelName or '..' == $modelName)) {
                $model = app_path('Models').'/'.$modelName;
                require_once $model;
                $class = '\\App\\Models\\'.basename($model, '.php');
                $instance = new $class();
                if ($instance->getTable() === $tableName) {
                    return $instance;
                }
            }
        }

        throw new Error('No model found');
    }
}
