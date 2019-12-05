<?php

namespace App\Collections;

use App\Utils\Helpers\DatabaseHelper;
use Illuminate\Database\Eloquent\Collection;
use const Illuminate\Database\Eloquent\INF;

class FlightLoadCollection extends Collection
{
    /**
     * Group an associative array by a field or using a callback.
     *
     * @param array|callable|string $groupBy
     * @param bool                  $preserveKeys
     *
     * @return static
     */
    public function groupBy($groupBy, $preserveKeys = false)
    {
        return new static(parent::groupBy($groupBy, $preserveKeys));
    }

    /**
     * Sorts collection using column.
     *
     * @param callable|string $column
     * @param int             $options Sorting method see https://php.net/manual/en/array.constants.php
     *
     * @return static
     */
    public function sortValues($column, bool $ascending = false, int $options = SORT_REGULAR)
    {
        if (DatabaseHelper::COLUMN_DOESNT_EXIST === $column || is_null($column)) {
            return $this;
        }

        return $this->sortBy($column, $options, !$ascending)
            ->values()
            ->all();
    }

    /**
     * Flattens collection according to depth.
     *
     * @param int $depth
     *
     * @return static
     */
    public function flatten($depth = INF)
    {
        return new static(parent::flatten($depth));
    }

    /**
     * Returns formatted values.
     *
     * @return static
     */
    public function formatByDate()
    {
        return new static(
            $this->map(function ($items) {
                return $items->map(function ($valuesByDate) {
                    return $valuesByDate->reduce(function ($accum, $value) {
                        $billedMeals = $value->billedMeals->first();
                        $flightPlan = $value->flightPlanPrices;
                        $accum['id'] = $value->flight_id;
                        $accum['date'] = $value->flight_date;
                        $accum['class'] = $billedMeals->class;
                        $accum['type'] = $billedMeals->type;
                        if ($flightPlan) {
                            $accum['plan_codes'] = explode(',', $flightPlan->iata_code);
                            $accum['plan_price'] = floatval($flightPlan->price);
                            $accum['plan_qty'] = floatval($flightPlan->meal_qty);
                        }
                        $accum['fact_codes'] = explode(',', $billedMeals->iata_code);
                        $accum['fact_qty'] = floatval($billedMeals->fact_qty);
                        $accum['fact_price'] = floatval($billedMeals->fact_price);
                        $accum['delta'] = $accum['plan_price'] - $accum['fact_price'];

                        return $accum;
                    }, [
                        'id' => null,
                        'date' => null,
                        'class' => null,
                        'type' => null,
                        'fact_qty' => 0,
                        'fact_codes' => [],
                        'fact_price' => 0,
                        'plan_qty' => 0,
                        'plan_codes' => [],
                        'plan_price' => 0,
                        'delta' => 0,
                    ]);
                });
            })
        );
    }
}
