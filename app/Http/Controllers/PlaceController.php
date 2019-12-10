<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityUpdateRequest;
use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CityUpdateRequest  $request
     * @param  string  $city
     * @return \Illuminate\Http\Response
     */
    public function update(CityUpdateRequest $request, string $city)
    {
        $attrs = $request->only('startHours', 'endHours', 'address');
        $place = Place::where('city', $city)->first();
        if(is_null($place)) return redirect()->back()->withErrors('Such city doesnt exists');
        foreach ($attrs as $key => $attr) {
            if(!is_null($attr)){
                $place[$key] = $attr;
            }
        }
        $place->save();
        return redirect()->back()->with('success', "Successfully updated {$city}");
    }
}
