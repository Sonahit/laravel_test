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
use App\Http\Controllers\BilledMealsController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->get('/billed_meals', function(Request $request){
        #TODO PAGING WITH API
        $controller = new BilledMealsController;
        $data = $controller->show(new App\Billed_Meals);
        return response()->json([
            'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'code' => '200'
        ]);
});


