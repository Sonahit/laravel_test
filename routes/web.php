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
Route::get('/city/{city}', "BookingController@show");
Route::post('/city/{city}', "BookingController@store");
Route::delete('/city/{city}', "BookingController@destroy");

Route::get('/users/profile', "UserController@show");
Route::post('/users/user', "UserController@update");

Route::prefix('company')->group(function () {
    Route::post('{city}', "PlaceController@update");
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
