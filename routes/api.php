<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function(){
    
    Route::get('/billed_meals', "BilledMealsController@index");
    Route::get('/pdf', 'ConverterController@pdf');
    Route::get('/csv', 'ConverterController@csv');
    
    Route::post('/pdf', 'ConverterController@index');
    Route::post('/csv', 'ConverterController@index');

});

