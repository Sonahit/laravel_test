<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Place;
use App\Utils\Helpers\RequestHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TemplateController extends Controller
{
    public function sendCalendar(Request $request)
    {
        $query = RequestHelper::queryToArray($request, ['date', 'city', 'week']);
        $week = is_null($query['week']) ? 0 : intval($query['week']);
        $city = is_null($query['city']) ? Place::select('city')->get()->first() : $query['city'];
        $date = is_null($query['date']) ? now() : Carbon::createFromFormat('Y-m-d', $query['date']);
        $startWeek = $date->addWeeks($week)->startOfWeek()->setTime(Booking::START, 0);
        $endWeek = $date->endOfWeek(6)->setTime(Booking::END, 0);
        $bookedDates = Booking::select('*')
            ->bookedBetween($startWeek, $endWeek)
            ->when(!is_null($city), function(Builder $q) use($city){
                $q->whereHas('places', function(Builder $sub) use($city){
                    $sub->where('city', $city);
                });
            })
            ->get();
        $week = collect(now()->getDays())->map(function($_, $index) use($date, $startWeek){
            return Carbon::createFromDate($date->year, $date->month, $startWeek->day + $index)->setTime(0, 0)->toDateString();
        });
        $html = view('templates.calendar', [
            'booked' => $bookedDates, 
            'week' => $week, 
            'bookTime' => [
                'start' => Booking::START,
                'end' => Booking::END
            ],
            'city' => $city
        ])->render();

        return response()->json(['html' => $html]);
    }
}
