<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', "CalendarController@show");


Route::post('/stakeHolders', 'StakeholdersController@store');
Route::delete('/stakeHolders', 'StakeholdersController@destroy');


Route::group(['prefix' => 'city'], function () {
    Route::get('{city}', "BookingController@show");
    Route::post('{city}', "BookingController@store");
    Route::delete('{city}', "BookingController@destroy");
    Route::put('{city}', "BookingController@update");
});



Route::group(['prefix' => 'users'], function () {
    Route::get('profile', "UserController@show");
    Route::post('user', "UserController@update");
    Route::get('link/{linkId}', "LinkController@show");
});


Route::prefix('company')->group(function () {
    Route::post('{city}', "PlaceController@update");
});

Route::prefix('auth')->group(function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::post('register', 'Auth\RegisterController@register');
    Route::get('register', "Auth\RegisterController@show");
});

Route::prefix('config')->group(function () {
    Route::post('/{config}', "ConfigurationController@update");
});

Route::prefix('templates')->group(function () {
    Route::get('calendar', 'TemplateController@sendCalendar');
});
