<?php

namespace App\Http\Controllers;

use App\Collections\Billed_Meals_Collection;
use App\Models\Billed_Meals;
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
        $query = get_params($request, 'paginate', 'sort', 'asc');
        $billed_meals_collection = $this->getData($billed_meals, $query);
        if($billed_meals instanceof Billed_Meals_Collection) return $billed_meals_collection.toJson();
        $billed_meals_transformed = groupByKey(flattenBilled($billed_meals_collection), true, 'date');
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
    
    public function getData(Billed_Meals $billed_meals, array $query)
    {
        $paginate = $query['paginate'];
        $sort = $query['sort'];
        $asc = $query['asc'];
        if(!$paginate) $paginate = $billed_meals->getPerPage();
        if(!$sort) $sort = "default";
        if(!$asc) $asc = 1;

        $relations = [
            'flight_load:business',
            'billed_meals_info:name,iata_code',
            'billed_meals_prices:billed_meals_id,qty,price_per_one',
            'new_matrix:new_matrix.meal_id,new_matrix.iata_code,new_matrix.passenger_amount,new_matrix.meal_qty'
        ];

        $billed_meals_base = $billed_meals->januaryBusiness()
            ->whereDoesntHave('billed_meals_info', function($q){
                $q->where('iata_code', 'ALC');
            })
            ->with($relations);
        $billed_meals_collect = $billed_meals_base->sort($sort, $asc);
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

function get_params(Request $request, ...$params){
    $query = [];
    foreach ($params as $param) {
        $query[$param] = $request->query($param);
    }
    return $query;
}


//To helper functions

function flattenBilled($billed_meals_collection){
  $billed_meals = array();
  foreach ($billed_meals_collection as $billed_meal) {
    $billed_prices = $billed_meal->billed_meals_prices;
    $billed_info = $billed_meal->billed_meals_info;
    $planned = [
      'codes' => array(),
      'qty' => 0,
      'price' => 0,
    ];
    $fact = [
      'codes' => array($billed_info->iata_code),
      'qty' => $billed_prices->qty,
      'price' => $billed_prices->price_per_one * $billed_prices->qty * 1.04 * 1.18,
    ];
    if($billed_meal->new_matrix) {
      $new_matrix_collection = $billed_meal->new_matrix;
    } else {
      $new_matrix_collection = array();
    };
    foreach ($new_matrix_collection as $nm) {
        $business_prices = $nm->meal_info->business_meal_prices;
        $planned['price'] += $business_prices->price * $nm->meal_qty;
        $planned['qty'] += $nm->meal_qty;
        push_if_not_exists($planned['codes'], $nm->iata_code);
      }
    array_push($billed_meals,
      [
        "id" => $billed_meal->flight_id,
        "date" => $billed_meal->flight_date,
        "class" => $billed_meal->class,
        "type" => $billed_meal->type,
        "plan_attributes" => $planned,
        "fact_attributes" => $fact
      ]);
  };
  return $billed_meals;
}

function groupByKey($billed_meals_collection, $do_group, $key){

  if(!$do_group) return $billed_meals_collection;

  $grouped_collection = $billed_meals_collection;
  //Should be index of elements with key occures more than 1
  $sameDate = findBySameKey($grouped_collection, $key);

  //Grouping
  foreach ($sameDate as $key => $indexes) {
    $start = $sameDate[$key][0];
    $plan_group = [
      'codes' => array(),
      'qty' => 0,
      'price' => 0,
    ];
    $fact_group = [
      'codes' => array(),
      'qty' => 0,
      'price' => 0,
    ];

    //Sum grouped elements
    foreach ($indexes as $index) {
      $el = $grouped_collection[$index];
      //Fact group
      $fact = $el['fact_attributes'];
      $plan = $el['plan_attributes'];
      $code = $fact['codes'][0];
      if(!in_array($code, $fact_group['codes'], true)){
        array_push($fact_group['codes'], $code);
      }
      $fact_group['qty'] += $fact['qty'];
      $fact_group['price'] += round($fact['price'], 2);
      //Plan group  
      $plan_group['qty'] = $plan['qty'];
      $plan_group['price'] = round($plan['price'], 2);
      if($index !== $start) $grouped_collection[$index] = [];
    }
    $grouped_collection[$start]['fact_attributes'] = $fact_group;
    $grouped_collection[$start]['plan_attributes'] = $plan_group;
  }
  // return $billed_meals_collection;
  return $grouped_collection;
}

function findBySameKey(array $array, string $key){
  $same = [];
  for ($i = 0; $i < count($array); $i++) {
    $element = $array[$i];
    $key_s = $element[$key];
    for ($j = $i + 1; $j < count($array); $j++) {
      $checkEl = $array[$j];
      if($checkEl[$key] === $key_s){
        if(!array_key_exists($key_s, $same)){
          $same[$key_s] = [$i];
        }
        if(!in_array($j, $same[$key_s], true)){
          array_push($same[$key_s], $j);
        }
      }
    }
  }
  return $same;
}

function push_if_not_exists(Array &$array, $element = null){
  if(!in_array($element, $array, true)){
    array_push($array,$element);
    return true;
  }
  return false;
}
