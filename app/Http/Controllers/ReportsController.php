<?php

namespace App\Http\Controllers;

use App\Collections\Flight_Load_Collection;
use App\Models\Billed_Meals;
use App\Models\Flight_Load;
use App\Utils\Helpers\DatabaseHelper;
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
    $query = RequestHelper::get_params_as_array($request, RequestHelper::params);
    $paginate = $query["paginate"];
    $page = $query["page"];
    $sortParam = $this->resolveSearchParam($query['sortParam']);
    $desc = !$this->strToBool(is_null($query['asc']) ? 'true' : $query['asc']);
    if($paginate === 0 || is_null($paginate) || is_null($page)) $page = 1;
    if(is_null($paginate)) $paginate = 40;

    $start = now();
    $table = $this->getData($flight_load, $query);
    $flight_load_transformed = $table
        ->groupBy(["flight_id", "flight_date"])
        ->formatByDate()
        ->flatten(1)
        ->sortValues($query['sortParam'], $desc);
    $end = now();

    $computeTime = abs($end->millisecond - $start->millisecond);
    return $this->getResponse([
        "pages" => $flight_load_transformed,
        "time" => $computeTime,
        "page" => $page,
        "perPage" => $paginate
    ], 200);
  }
  /**
   * Get JSON responce
   * 
   * @param any $data
   * @param int $code
   */
  protected function getResponse($data, int $code)
  {
    Log::info("Sending response", ["code" => $code]);
    return new Response(json_encode($data), $code, ["Content-Type" => "application/json"]);
  }
  
  private function strToBool(String $str)
  {
    if($str === "false") return false;
    return true;
  }

  /**
   * @param \App\Models\Flight_Load $flight_load
   * @param array $query
   * @return \App\Collections\Flight_Load_Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getData(Flight_Load $flight_load, array $query)
  {
    $paginate = is_null($query["paginate"]) || !$query["paginate"] 
      ? $flight_load->getPerPage()
      : intval($query["paginate"]);

    $page = intval($query["page"]);

    $searchParam = $this->resolveSearchParam($query["searchParam"]);

    $desc = is_null($query["asc"]) 
      ? false 
      : !$this->strToBool($query["asc"]);

    $sortParam = is_null($query["sortParam"]) 
      ? ['flight_load.flight_id', 'flight_load.flight_date'] 
      : [DatabaseHelper::paramToColumn($query["sortParam"])];

    $relations = [
      'billed_meals' => function($q){
          $q->select([
              'flight_load_id',
              DB::raw('ROUND(SUM(qty), 2) as fact_qty'),
              'class',
              'type',
              DB::raw('GROUP_CONCAT(DISTINCT iata_code) as iata_code'),
              'total as fact_price'
            ])
            ->groupBy("flight_id", "flight_date");
      },
      'flight_plan_prices'
    ];
    $flight_load_collect = $flight_load
        ->january()
        ->business()
        ->whereHas('billed_meals', function($q) use($searchParam){
            $q
              ->orWhereLike(Billed_Meals::searchableRows, $searchParam);
        })
        ->when(is_int($searchParam) && !$this->isDate($searchParam), function($sub) use($searchParam){
          $sub->orWhereHas('flight_plan_prices', function($query) use($searchParam){ 
            $query
              ->january()
              ->orWhereLike([
                'flight_plan_prices.meal_qty',
                'ROUND(flight_plan_prices.price, 2)',
                'ROUND(flight_plan_prices.delta, 2)'
              ], $searchParam);
          })
          ->orWhereHas('billed_meals', function($query) use($searchParam){
            $query
              ->orHavingLike(['ROUND(SUM(qty * price_per_one), 2)', 'SUM(qty)'], $searchParam)
              ->groupBy("flight_id", "flight_date");
          });
        })
        ->with($relations)
        ->sortBy($sortParam, $desc);
    if($page > 1 && $paginate < 1) return new Flight_Load_Collection();
    if($paginate < 1) return $flight_load_collect->get();
    return $flight_load_collect->simplePaginate($paginate);
  }

  private function isDate(string $value = '')
  {
    $pattern = '/^(\d{4})-([01][0-9])-([0-2][0-9]|[3][0-1])/i';
    return preg_match($pattern, $value);
  }

  private function resolveSearchParam($value)
  {
    if(is_null($value)){
      return "";
    } else if($this->isDate($value)){
      return $value;
    } else if(is_numeric($value)){
      return intval($value);
    } else if(!is_null($value)){
      return $value;
    }
    return "";
  }

  public function show()
  {
      Log::info("Serving index.php");
      return view("index");
  }   
}
