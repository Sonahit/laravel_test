<?php

namespace App\Http\Controllers;

use App\Models\Billed_Meals;
use App\Models\Flight_Load;
use App\Utils\Helpers\RequestHelper;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            "time" => $computeTime,
            "page" => $page,
            "perPage" => $paginate
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
        if(is_null($paginate) || !$paginate) $paginate = $flight_load->getPerPage();
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
                    DB::raw('GROUP_CONCAT(DISTINCT iata_code) as iata_code'),
                    DB::raw('ROUND(SUM(qty * price_per_one), 2) as fact_price')
                  ])
                  ->groupBy("flight_id", "flight_date");
            },
            'flight_plan_prices'
        ];
        
        $flight_load_collect = $flight_load
            ->january()
            ->business()
            ->sort($asc)
            ->where(function($sub) use($searchParam){
              $sub->whereHas('billed_meals', function($q) use($searchParam){
                $q
                  ->business()
                  ->noALC()
                  ->orWhereLike(Billed_Meals::searchableRows, $searchParam);
              });
            })
            ->when(is_int($searchParam) && !$this->isDate($searchParam), function($sub) use($searchParam){
              $sub->orWhereHas('flight_plan_prices', function($query) use($searchParam){ 
                $query
                  ->january()
                  ->orWhereLike([
                    'flight_plan_prices.meal_qty',
                    'flight_plan_prices.price'
                  ], $searchParam);
              })
              ->orWhereHas('billed_meals', function($query) use($searchParam){
                $query
                  ->business()
                  ->noALC()
                  ->orHavingLike(['ROUND(SUM(qty * price_per_one), 2)', 'SUM(qty)'], $searchParam)
                  ->groupBy("flight_id", "flight_date");
              });
            })
            ->with($relations);
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
