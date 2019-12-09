<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
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
     * @param  \App\Http\Requests\BookRequst  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        $query = RequestHelper::queryToArray($request, ['date', 'city', 'name', 'email']);
        
        if (is_null($query['city'])){
            return response()->json(['message' => 'City cannot be empty']);
        } elseif (is_null($query['name'])){
            return response()->json(['message' => 'Name cannot be empty']);
        } elseif (is_null($query['email'])){
            return response()->json(['message' => 'Email cannot be empty']);
        } elseif (is_null($query['date'])){
            return response()->json(['message' => 'Date cannot be empty']);
        }
        $city = $query['city'];
        $week = intval($query['week']);
        $date = Carbon::createFromTimestamp(intval($query['date']));
        $name = $query['name'];
        $email = $query['email'];
        $user = User::where(function($q){
            return $q->where('email', $email)
                    ->where('name', $name);
        });
        $place = Place::where('city', $city);
        Booking::firstOrNew();
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
        $cities = Place::select('city')->get()->map(function($place){
            return $place->city;
        });
        $week = is_null($query['week']) ? -1 : intval($query['week']) - 1;
        $startHours = config('app.startHours');
        $endHours = config('app.endHours');
        $date = is_null($query['date']) ? now() : Carbon::createFromFormat('Y-m-d', $query['date']);
        $startWeek = $date->addWeeks($week)->startOfWeek()->setTime($startHours, 0);
        $endWeek = $date->endOfWeek(6)->setTime($endHours, 0);
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
        
        return view('index', [
            'time' => now(), 
            'booked' => $bookedDates, 
            'week' => $week, 
            'bookTime' => [
                'start' => $startHours,
                'end' => $endHours
            ],
            'cities' => $cities
        ]);
    }

    public function showBooking(Request $request)
    {
        $query = RequestHelper::queryToArray($request, ['time']);
        $path = str_replace('/', '', $request->getPathInfo());
        $city = Place::where('city', $path)->first();
        $dateTime = $query['time'];
        if(is_null($city) || is_null($dateTime)) return redirect('/');
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
