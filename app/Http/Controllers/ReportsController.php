<?php

namespace App\Http\Controllers;

use App\Collections\FlightLoadCollection;
use App\Models\BilledMeals;
use App\Models\FlightLoad;
use App\Utils\Helpers\DatabaseHelper;
use App\Utils\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FlightLoad $flightLoad, Request $request)
    {
        $query = RequestHelper::getParamsAsArray($request, RequestHelper::PARAMS);
        $paginate = $query['paginate'];
        $page = $query['page'];
        $asc = $this->strToBool(is_null($query['asc']) ? 'true' : $query['asc']);
        if (0 === $paginate || is_null($paginate) || is_null($page)) {
            $page = 1;
        }
        if (is_null($paginate)) {
            $paginate = 40;
        }

        $start = now();
        $table = $this->getData($flightLoad, $query);
        $flightLoad_transformed = $table
            ->groupBy(['flight_id', 'flight_date'])
            ->formatByDate()
            ->flatten(1)
            ->sortValues($query['sortParam'], $asc)
        ;
        $end = now();

        $computeTime = abs($end->millisecond - $start->millisecond);

        return $this->getResponse([
            'pages' => $flightLoad_transformed,
            'time' => $computeTime,
            'page' => $page,
            'perPage' => $paginate,
        ], 200);
    }

    /**
     * Fetch data.
     *
     * @return \App\Collections\FlightLoadCollection|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function getData(FlightLoad $flightLoad, array $query)
    {
        $paginate = is_null($query['paginate']) || !$query['paginate']
            ? $flightLoad->getPerPage()
            : intval($query['paginate']);

        $page = intval($query['page']);

        $searchParam = $this->resolveSearchParam($query['searchParam']);

        $asc = is_null($query['asc'])
            ? true
            : $this->strToBool($query['asc']);

        $sortParam = is_null($query['sortParam'])
            ? ['flight_load.flight_id', 'flight_load.flight_date']
            : [DatabaseHelper::paramToColumn($query['sortParam'])];

        /**
         * Initializing relationships.
         */
        $relations = [
            'billedMeals' => function ($q) {
                $q->select([
                    'flight_load_id',
                    DB::raw('ROUND(SUM(qty), 2) as fact_qty'),
                    'class',
                    'type',
                    DB::raw('GROUP_CONCAT(DISTINCT iata_code) as iata_code'),
                    'total as fact_price',
                ])->groupBy('flight_id', 'flight_date');
            },
            'flightPlanPrices',
        ];

        /**
         * Initializing query to fetch data.
         */
        $flightLoadCollector = $flightLoad
            ->january()
            ->business()
            ->whereHas('billedMeals', function ($q) use ($searchParam) {
                $q->orWhereLike(BilledMeals::SEARCHABLE_ROWS, $searchParam);
            })
            ->when(is_int($searchParam) && !$this->isDate($searchParam), function ($sub) use ($searchParam) {
                $sub->orWhereHas('flightPlanPrices', function ($query) use ($searchParam) {
                    $query
                    ->january()
                    ->orWhereLike([
                        'flight_plan_prices.meal_qty',
                        'ROUND(flight_plan_prices.price, 2)',
                        'ROUND(flight_plan_prices.delta, 2)',
                    ], $searchParam);
                })
                ->orWhereHas('billedMeals', function ($query) use ($searchParam) {
                    $query
                    ->orHavingLike(['ROUND(SUM(qty * price_per_one), 2)', 'SUM(qty)'], $searchParam)
                    ->groupBy('flight_id', 'flight_date');
                });
            })
            ->with($relations)
            ->sortBy($sortParam, $asc);
        if ($page > 1 && $paginate < 1) {
            return new FlightLoadCollection();
        }
        if ($paginate < 1) {
            return $flightLoadCollector->get();
        }

        return $flightLoadCollector->simplePaginate($paginate);
    }

    public function show()
    {
        Log::info('Serving index.php');

        return view('index');
    }

    /**
     * Get JSON responce.
     *
     * @param mixed $data
     * @param int $code
     *
     * @return \Illuminate\Http\Response
     */
    protected function getResponse($data, int $code)
    {
        Log::info('Sending response', ['code' => $code]);

        return new Response(json_encode($data), $code, ['Content-Type' => 'application/json']);
    }

    /**
     * Convert string to boolean.
     *
     * @param string $str
     *
     * @return bool
     */
    private function strToBool(string $str)
    {
        if ('false' === $str) {
            return false;
        }

        return true;
    }

    /**
     * Check if date.
     *
     * @param string $value
     *
     * @return bool
     */
    private function isDate(string $value = '')
    {
        $pattern = '/^(\d{4})-([01][0-9])-([0-2][0-9]|[3][0-1])/i';

        return preg_match($pattern, $value) ? true : false;
    }

    /**
     * Resolve incoming value to its string representations or numeric
     *
     * @param mixed $value
     *
     * @return string|int
     */
    private function resolveSearchParam($value)
    {
        if (is_null($value)) {
            return '';
        }
        if ($this->isDate($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return intval($value);
        }
        if (!is_null($value)) {
            return $value;
        }

        return '';
    }
}
