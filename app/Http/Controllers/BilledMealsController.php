<?php

namespace App\Http\Controllers;

use App\Collections\Billed_Meals_Collection;
use App\Models\Billed_Meals;
use App\Utils\Helpers\RequestHelper;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BilledMealsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Billed_Meals $billed_meals, Request $request)
    {
        $query = RequestHelper::get_params_as_array($request, "paginate", "sort", "asc", "page");
        $paginate = $query['paginate'];
        $page = $query['page'];
        if($paginate < 0 || !$paginate) $page = 1;
        if(!$paginate) $paginate = 40;
        $key = "{$paginate}={$page}";
        $keyTotal = "{$paginate}=total";
        $start = now();
        if(Cache::has($key) && Cache::has($keyTotal)){
            $billed_meals_transformed = json_decode(Cache::get($key));
            if($paginate < 0) return $this->getResponse(['pages' => $billed_meals_transformed], 200);
        } else {
            $time = now()->addMinutes(2);
            $billed_meals_collection = $this->getData($billed_meals, $query);
            $billed_meals_transformed = $billed_meals_collection
                ->groupBy("flight_id")
                ->formatByDate()
                ->flatten(1);
            Cache::put($key, json_encode($billed_meals_transformed), $time);
            $end = now();
            $computeTime = abs($end->millisecond - $start->millisecond);
            if($billed_meals_collection instanceof Billed_Meals_Collection) {
                Cache::put($keyTotal,$billed_meals_collection->count(), $time);
                return $this->getResponse(['pages' => $billed_meals_transformed, 'time' => $computeTime], 200);
            };
            Cache::put($keyTotal,$billed_meals_collection->total(), $time);
        }
        $end = now();
        $computeTime = abs($end->millisecond - $start->millisecond);
        $total = Cache::get($keyTotal);
        $pages = new \Illuminate\Pagination\LengthAwarePaginator(
            $billed_meals_transformed,
            $total,
            $paginate,
            $page, 
            [
                "path" => \Request::url(),
                "query" => [
                    "page" => $page
                ]
            ]
        );
        return $this->getResponse([
            'pages' => $pages,
            'time' => $computeTime
        ], 200);
    }

    protected function getResponse($data, int $code){
        return new Response(json_encode($data), $code,['Content-Type' => "application/json"]);
    }
    
    /**
     * @return \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator 
     */
    public function getData(Billed_Meals $billed_meals, array $query)
    {
        $paginate = $query["paginate"];
        $asc = $query["asc"];
        if(!$paginate) $paginate = $billed_meals->getPerPage();
        if(!$asc) $asc = 1;

        $relations = [
            "billed_meals_info" => function ($q){
                $q -> select(
                    "name",
                    "iata_code"
                );
            },
            "billed_meals_prices" => function($q){
                $q -> select("billed_meals_id", 
                    "qty", 
                    "price_per_one"
                );
            },
            "new_matrix" => function($q){
                $q -> select(
                    "new_matrix.meal_id",
                    "new_matrix.iata_code",
                    "new_matrix.meal_qty",
                    "new_matrix.passenger_amount",
                );
            }
        ];

        $billed_meals_collect = $billed_meals->januaryBusiness()
            ->whereDoesntHave("billed_meals_info", function($q){
                $q->where("iata_code", "ALC");
            })
            ->with($relations)
            ->sort($asc);
        // dd($billed_meals_collect);
        // $test = $billed_meals_collect->paginate($paginate);
        // dd($test);
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
