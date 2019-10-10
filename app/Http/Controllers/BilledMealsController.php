<?php

namespace App\Http\Controllers;

use App\Billed_Meals;
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
    public function show(Billed_Meals $billed_Meals)
    {
        //#TODO GET CERTAIN DATA BY MODEL
        $billed_meal = Billed_Meals::find(1, $billed_Meals->getFillable())->getOriginal();
        return view('billed_meals.index', ['billed_meal' => $billed_meal]);
    }   

}
