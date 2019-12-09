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

Route::get('/', "BookingController@show");
Route::get('/{city}', "BookingController@showBooking");
Route::post('/{city}', "BookingController@store");


Route::prefix('users')->group(function(){
    Route::get('profile', "UserController@show");
});

Route::prefix('auth')->group(function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::post('register', 'Auth\RegisterController@register');
    Route::get('register', "Auth\RegisterController@show");
});

Route::prefix('templates')->group(function () {
    Route::get('calendar', 'TemplateController@sendCalendar');
});
