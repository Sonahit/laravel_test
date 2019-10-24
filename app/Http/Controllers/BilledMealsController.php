<?php

namespace App\Http\Controllers;

use App\Models\Billed_Meals;
use Illuminate\Database\Eloquent\Collection;
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
        $relations = [
            'flight_load',
            'billed_meals_info',
            'billed_meals_prices',
            'new_matrix'
        ];
        $billed_meals_collect = $billed_meals->januaryBusiness()
            ->sort()
            ->whereDoesntHave('billed_meals_info', function($q){
                $q->where('iata_code', 'ALC');
            })
            ->with($relations)
            ->paginate();
        // echo $billed_meals_base_collect;
        return view('index', [
            'billed_meals_collect' => $billed_meals_collect,
            'links'=> $billed_meals_collect->links()
         ]);
    }   
}
