<?php

namespace App\Http\Controllers;

use App\Billed_Meals;
use App\Flight_Load;
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
        $billed_meals = Billed_Meals::with(['flight_load', 'meal_rules'])
        ->where($billed_meals->where)
        ->where('flight_id', '=', 39)
        ->limit(1);
            //->where('flight_date', '=', '2017-01-06 12:05:00')
        $data_bms=$billed_meals->where('flight_id', '=', 39)
            //->where($billed_meals->where)
            ->limit(1)
            ->get();
        $billed_meals->dump();
        $data_bms->dump();
    }   
}
