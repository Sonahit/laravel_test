<?php

namespace App\Http\Controllers;

use App\Collections\Billed_Meals_Collection;
use App\Models\Billed_Meals;
use App\Utils\Helpers\DatabaseHelper;
use Illuminate\Http\Request;

class BilledMealsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Billed_Meals $billed_meals, Request $request)
    {
        $query = get_params_as_array($request, 'paginate', 'sort', 'asc');
        $billed_meals_collection = $this->getData($billed_meals, $query);
        if($billed_meals_collection instanceof Billed_Meals_Collection) {
          return DatabaseHelper::groupByKey(DatabaseHelper::flattenBilled($billed_meals_collection), true, 'date');
        }
        $billed_meals_transformed = DatabaseHelper::groupByKey(DatabaseHelper::flattenBilled($billed_meals_collection), true, 'date');
        $pages = new \Illuminate\Pagination\LengthAwarePaginator(
            $billed_meals_transformed,
            $billed_meals_collection->total(),
            $billed_meals_collection->perPage(),
            $billed_meals_collection->currentPage(), [
                'path' => \Request::url(),
                'query' => [
                    'page' => $billed_meals_collection->currentPage()
                ]
            ]
        );
        return $pages;
    }
    
    public function getData(Billed_Meals $billed_meals, array $query)
    {
        $paginate = $query['paginate'];
        $asc = $query['asc'];
        if(!$paginate) $paginate = $billed_meals->getPerPage();
        if(!$asc) $asc = 1;

        $relations = [
            'flight_load' => function ($q){
              $q -> select('business');
            },
            'billed_meals_info' => function ($q){
              $q -> select('name', 'iata_code');
            },
            'billed_meals_prices' => function($q){
              $q -> select('billed_meals_id', 'qty', 'price_per_one');
            },
            'new_matrix' => function($q){
              $q -> select('new_matrix.meal_id', 'new_matrix.iata_code', 'new_matrix.passenger_amount', 'new_matrix.meal_qty');
            }
        ];

        $billed_meals_base = $billed_meals->januaryBusiness()
            ->whereDoesntHave('billed_meals_info', function($q){
                $q->where('iata_code', 'ALC');
            })
            ->with($relations);
        $billed_meals_collect = $billed_meals_base->sort($asc);
        if($paginate < 1) return $billed_meals_collect->get();
        return $billed_meals_collect->paginate($paginate);
    }    
    /**
     * Display the specified resource.
     *
     * @param  \App\Billed_Meals  $billed_Meals
     * @return \Illuminate\Http\Response
     */
    public function show(Billed_Meals $billed_meals, Request $request)
    {
        //TODO: caching
        $billed_meals_collection = $this->index($billed_meals, $request);
        return view('index', [
            'billed_meals_collection' => $billed_meals_collection
        ]);
    }   
}

function get_params_as_array(Request $request, ...$params){
    $query = [];
    foreach ($params as $param) {
        $query[$param] = $request->query($param);
    }
    if(isset($_COOKIE['paginate'])) $query['paginate'] = intval($_COOKIE['paginate']);
    return $query;
}
