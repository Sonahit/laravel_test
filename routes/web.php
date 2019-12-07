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

Route::get('/', "UserController@show");
Route::get('/register', "Auth\RegisterController@show");

Route::group(['prefix' => 'auth', 'middleware' => ['web']], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::post('register', 'Auth\RegisterController@register');
});
