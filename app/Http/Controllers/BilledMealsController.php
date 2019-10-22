<?php

namespace App\Http\Controllers;

use App\Collections\Billed_Meals_Collection;
use App\Models\Billed_Meals;
use Illuminate\Http\Request;

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
            'name',
            'delivery_number'
        ];
        $relations = [
            'flight_load:id,business',
            'billed_meals_info:name,iata_code',
            'billed_meals_prices:billed_meals_prices.qty,billed_meals_prices.price_per_one,billed_meals_prices.total,billed_meals_prices.total_novat_discounted'
        ];
        $billed_meals_collect = $billed_meals
            ->januaryBusiness()
            ->sort()
            ->with($relations)
            ->paginate()
            ->withNewMatrix()
            ->flatCollection();
        $billed_meals_collect->dump();
    }   
}
