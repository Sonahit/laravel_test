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
        //#TODO CONNECT new_matrix through flight_load and meal_rules
        /*$billed_meals_query=Billed_Meals::with('flight_load')
            ->with('meal_rules')
            ->orderBy('flight_id', 'asc')
            ->orderBy('flight_date', 'asc')
            ->groupBy('flight_id', 'flight_date');*/
         //#Todo recode for pagination
         $billed_meals_query=Billed_Meals::chunk(15, function($billed_meals){
             foreach ($billed_meals as $billed_meal) {
                 $meal_rules = $billed_meal->meal_rules()->where('iata_code', $billed_meal->iata_code)->get();
                 $flight_load = $billed_meal->flight_load()->where('id', $billed_meal -> flight_load_id)->get();
                 $new_matrix = New_Matrix::all()
                    ->where('passenger_amount', $flight_load->business)
                    ->where('iata_code', $meal_rules->iata_code);
                break;
            }
         }) ->orderBy('flight_id', 'asc')
            ->orderBy('flight_date', 'asc')
            ->groupBy('flight_id', 'flight_date');
        $billed_meals_collect = $billed_meals_query->paginate(10);
        $billed_meals_query->dump();
        $billed_meals_collect->dump();
    }   
}
