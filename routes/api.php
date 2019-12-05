<?php

use Illuminate\Support\Facades\Route;

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

Route::middleware('api')->group(function () {
        Route::get('/billed_meals', 'ReportsController@index');
        Route::get('/pdf', 'ConverterController@pdf');
        Route::get('/csv', 'ConverterController@csv');
        Route::post('/pdf', 'ConverterController@index');
        Route::post('/csv', 'ConverterController@index');
});
