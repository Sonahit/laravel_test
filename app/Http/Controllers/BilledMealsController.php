<?php

namespace App\Http\Controllers;

use App\Billed_Meals;
// #TODO Get rid of it in near future
use App\New_Matrix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BilledMealsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Billed_Meals  $billed_Meals
     * @return \Illuminate\Http\Response
     */
    public function show(Billed_Meals $billed_meals)
    {
        $rows = ['flight_id',
            'flight_date',
            'type', 
            'class',
            'iata_code as code.fact',
            'qty as qty.fact',
            DB::raw('Round(price_per_one, 2) as `price.fact`')
        ];
        $billed_meals_collect = $billed_meals
            ->sort()
            ->with(['flight_load', 'meal_rules','billed_meals_info'])
            ->with(['billed_meals_price'])
            ->with(['new_matrix'])
            ->paginate();
        $billed_meals_collect->new_matrix->business_meal_prices;
        $billed_meals_collect->dump();
    }   
}
