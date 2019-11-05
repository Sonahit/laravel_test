<?php

namespace App\Http\Controllers;

use App\Collections\Billed_Meals_Collection;
use App\Models\Billed_Meals;
use App\Utils\Helpers\RequestHelper;
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
        $query = RequestHelper::get_params_as_array($request, "paginate", "sort", "asc");
        $billed_meals_collection = $this->getData($billed_meals, $query);
        if($billed_meals_collection instanceof Billed_Meals_Collection) {
          return [
              'pages' => $billed_meals_collection
                    ->groupBy("flight_id")
                    ->formatByDate()
                    ->flatten(1)
          ];
        }
        $billed_meals_transformed = $billed_meals_collection
                ->groupBy("flight_id")
                ->formatByDate()
                ->flatten(1);
        $pages = new \Illuminate\Pagination\LengthAwarePaginator(
            $billed_meals_transformed,
            $billed_meals_collection->total(),
            $billed_meals_collection->perPage(),
            $billed_meals_collection->currentPage(), [
                "path" => \Request::url(),
                "query" => [
                    "page" => $billed_meals_collection->currentPage()
                ]
            ]
        );
        return [
            'pages' => $pages,
            'html' => $pages->links()->toHtml()
        ];
    }
    
    public function getData(Billed_Meals $billed_meals, array $query)
    {
        $paginate = $query["paginate"];
        $asc = $query["asc"];
        if(!$paginate) $paginate = $billed_meals->getPerPage();
        if(!$asc) $asc = 1;

        $relations = [
            "flight_load" => function ($q){
              $q -> select("business");
            },
            "billed_meals_info" => function ($q){
              $q -> select(
                "name",
               "iata_code");
            },
            "billed_meals_prices" => function($q){
              $q -> select("billed_meals_id", 
              "qty", 
              "price_per_one");
            },
            "new_matrix" => function($q){
              $q -> select(
                "new_matrix.meal_id",
                "new_matrix.iata_code",
                "new_matrix.meal_qty",
                "new_matrix.passenger_amount");
            }
        ];

        $billed_meals_collect = $billed_meals->januaryBusiness()
            ->whereDoesntHave("billed_meals_info", function($q){
                $q->where("iata_code", "ALC");
            })
            ->with($relations)
            ->sort($asc);
        if($paginate < 1) return $billed_meals_collect->get();
        return $billed_meals_collect->paginate($paginate);
    }    

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view("index");
    }   
}
