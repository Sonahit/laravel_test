<?php

namespace App\Http\Controllers;

use App\Billed_Meals;
use App\Flight_Load;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
      //  $billed_meals_query = Billed_Meals::find(1);
      //  $flight_load = $billed_meals->flight_load;
      //  $meal_rules = $billed_meals->meal_rules;
    /*
        
    
        ->whereHas('meal_rules', function($query){
            echo $query;
        })
        ;*/
        //#TODO CONNECT new_matrix through flight_load and meal_rules
        $billed_meals_query=Billed_Meals::with(['flight_load', 'meal_rules', 'meal_rules.new_matrix'])
            ->whereBetween('flight_date', ['20170101', '20170131'])
            ->where($billed_meals->where)
            ->orderBy('flight_id', 'asc')
            ->orderBy('flight_date', 'asc')
            ->groupBy('flight_id', 'flight_date');
        $billed_meals_collect = $billed_meals_query->paginate(15);
        $billed_meals_query->dump();
        $billed_meals_collect->dump();
    }   
}
