<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;

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



// Route::middleware('auth:api')->group(function () {
//     Route::get('calendar', 'GoogleController@index');
// });

Route::middleware('api')->group(function () {
    Route::get('calendar', 'GoogleController@index');
    Route::get('calendar/token', 'GoogleController@updateToken');
});

Route::middleware('api')->group(function () {
    Route::get('token', 'ApiController@index');
});
