<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Place;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Utils\Helpers\RequestHelper;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Show booking calendar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $query = RequestHelper::queryToArray($request, ['date', 'city', 'week']);
        $city = $query['city'];
        $week = is_null($query['week']) ? 0 : intval($query['week']);
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
        $cities = Place::select('city')->get()->map(function($place){
            return $place->city;
        });
        return view('index', [
            'time' => now(), 
            'booked' => $bookedDates, 
            'week' => $week, 
            'bookTime' => [
                'start' => Booking::START,
                'end' => Booking::END
            ],
            'cities' => $cities
        ]);
    }

    public function showBooking(Request $request)
    {
        $query = RequestHelper::queryToArray($request, ['city', 'dateTime']);
        $city = $query['city'];
        $dateTime = $query['dateTime'];
        if(is_null($city) || is_null($dateTime)) redirect('/');
        return view('booking', ['city' => $city, 'dateTime' => $dateTime]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
