<?php

namespace App\Http\Controllers;

use App\Models\Billed_Meals;
use App\Models\Flight_Load;
use App\Utils\Helpers\RequestHelper;
use Dotenv\Regex\Regex;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Flight_Load $flight_load, Request $request)
    {
        $query = RequestHelper::get_params_as_array($request, "paginate", "sort", "asc", "page", "searchParam");
        $paginate = $query["paginate"];
        $page = $query["page"];

        if($page > 1 && $paginate < 0){
            return $this->getResponse([], 204);
        }
        if($paginate === 0 || is_null($paginate) || is_null($page)) $page = 1;
        if(is_null($paginate)) $paginate = 40;

        $start = now();

        $flight_load_collection = $this->getData($flight_load, $query);
        $flight_load_transformed = $flight_load_collection
            ->groupBy(["flight_id", "flight_date"])
            ->formatByDate()
            ->flatten(1);

        $end = now();

        $computeTime = abs($end->millisecond - $start->millisecond);
        return $this->getResponse([
            "pages" => $flight_load_transformed,
            "time" => $computeTime
        ], 200);
    }

    protected function getResponse($data, int $code){
        Log::info("Sending response", ["code" => $code]);
        return new Response(json_encode($data), $code, ["Content-Type" => "application/json"]);
    }
    
    /**
     * @return \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator 
     */
    public function getData(Flight_Load $flight_load, array $query)
    {
        $paginate = intval($query["paginate"]);
        $asc = $query["asc"];
        if(is_null($paginate)) $paginate = $flight_load->getPerPage();
        if(is_null($asc)) $asc = true;
        if($this->isDate($query["searchParam"])){
          $searchParam = $query["searchParam"];
        } else if(is_numeric($query["searchParam"])){
          $searchParam = intval($query["searchParam"]);
        } else if(!is_null($query["searchParam"])){
          $searchParam = $query["searchParam"];
        } else {
          $searchParam = "";
        }
        $relations = [
            'billed_meals' => function($q) {
                $q->business()
                  ->noALC()
                  ->select([
                    'flight_load_id',
                    DB::raw('ROUND(SUM(qty), 2) as fact_qty'),
                    'class',
                    'type',
                    DB::raw('GROUP_CONCAT(DISTINCT iata_code) as fact_codes'),
                    DB::raw('ROUND(SUM(qty * price_per_one), 2) as fact_price')
                  ])
                  ->groupBy("flight_id", "flight_date");
            },
            'new_matrix' => function($q) {
              $q->select(
                "new_matrix.iata_code",
                DB::raw("ROUND(SUM(new_matrix.meal_qty), 2) as plan_qty"),
                "new_matrix.passenger_amount",
                "new_matrix.nomenclature",
                DB::raw("ROUND(SUM(business_meal_prices.price * new_matrix.meal_qty), 2) as plan_price")
              )
              ->groupBy("new_matrix.iata_code", "billed_meals.id");
            }
        ];
        
        $flight_load_collect = $flight_load
            ->january()
            ->business()
            ->sort($asc)
            ->where(function($sub) use($searchParam){
              $sub->whereHas('billed_meals', function($q) use($searchParam){
                $q->business()  
                  ->noALC()
                  ->whereLike(Billed_Meals::searchableRows, $searchParam);
              })
              ->when(is_int($searchParam), function($q) use($searchParam){
                $q->orWhereHas('new_matrix', function($query) use($searchParam){ 
                    $query
                    ->join('business_meal_prices', function ($join){
                      $join->on('business_meal_prices.nomenclature', '=', 'new_matrix.nomenclature');
                    })
                    ->havingLike([
                        'SUM(DISTINCT `new_matrix`.`meal_qty`)',
                        'SUM(DISTINCT `business_meal_prices`.`price` * `new_matrix`.`meal_qty`)'
                    ], $searchParam)
                    ->groupBy('new_matrix.passenger_amount');
                });
              });
            })
            ->with($relations)
            ->groupBy("flight_id", "flight_date");
        //     $relations = [
        //     'billed_meals' => function($q) {
        //         $q->business()
        //           ->noALC()
        //           ->select([
        //             'flight_load_id',
        //             DB::raw('ROUND(SUM(qty), 2) as fact_qty'),
        //             'class',
        //             'type',
        //             DB::raw('GROUP_CONCAT(DISTINCT iata_code) as fact_codes'),
        //             DB::raw('ROUND(SUM(qty * price_per_one), 2) as fact_price')
        //           ])
        //           ->groupBy("flight_id", "flight_date");
        //     },
        //     'new_matrix_prices' => function($q){
        //       $q->select(
        //       'iata_code',
        //       'new_matrix_prices.meal_qty as plan_qty', 
        //       'new_matrix_prices.passenger_amount', 
        //       'new_matrix_prices.price as plan_price',
        //       'new_matrix_prices.nomenclature');
        //     }
        // ];
        
        // $flight_load_collect = $flight_load
        //     ->january()
        //     ->business()
        //     ->sort($asc)
        //     ->where(function($sub) use($searchParam){
        //       $sub->whereHas('billed_meals', function($q) use($searchParam){
        //         $q->business()  
        //           ->noALC()
        //           ->whereLike(Billed_Meals::searchableRows, $searchParam);
        //       })
        //       ->when(is_int($searchParam), function($q) use($searchParam){
        //         $q->orWhereHas('new_matrix_prices', function($query) use($searchParam){ 
        //             $query
        //             // ->where('flight_load.business', 'new_matrix_prices.passenger_amount')
        //             ->whereLike(['new_matrix_prices.price', 'new_matrix_prices.meal_qty'], $searchParam);
        //         });
        //       });
        //     })
        //     ->with($relations)            
        //     ->groupBy("flight_id", "flight_date");
        if($paginate < 1) return $flight_load_collect->get();
        return $flight_load_collect->simplePaginate($paginate);
    }    

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function isDate($value){
      $pattern = '/^(\d{4})-([01][0-9])-([0-2][0-9]|[3][0-1])/i';
      return preg_match($pattern, $value);
    }
    public function show()
    {
        Log::info("Serving index.php");
        return view("index");
    }   
}
