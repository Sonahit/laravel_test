<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Booking;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Utils\Helpers\RequestHelper;
use App\Utils\Traits\PrepareCalendar;

class BookingController extends Controller
{

    use PrepareCalendar;
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
     * Create \App\Models\Booking instance.
     *
     * @param string $email
     * @param string $city
     * @param \Illuminate\Support\Carbon $from
     * @param \Illuminate\Support\Carbon $to
     * 
     * @return array
     */
    public function create(string $email, string $city, Carbon $from, Carbon $to)
    {
        $userId = User::where(function($q) use($email){
            return $q->where('email', $email);
        })->first()->id;
        $placeId = Place::where('city', $city)->first()->id;
        $booking = Booking::updateOrCreate([
            'userId' => $userId,
            'placeId' => $placeId,
            'bookingDateStart' => $from->toDateTimeString(),
            'bookingDateEnd' => $to->toDateTimeString()
        ])->first();
        return [$booking, $booking->exists];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BookRequst  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        $query = $request->only('time', 'city', 'firstName', 'lastName', 'email');
        if (is_null($query['city'])){
            return response()->json(['message' => 'City cannot be empty']);
        } elseif (is_null($query['email'])){
            return response()->json(['message' => 'Email cannot be empty']);
        } elseif (is_null($query['time'])){
            return response()->json(['message' => 'Date cannot be empty']);
        }
        $email = $query['email'];
        $city = $query['city'];
        $start = Carbon::createFromTimestamp(intval($query['time']));
        $end = Carbon::createFromTimestamp(intval($query['time']))->addHours(2);
        [$booking, $exists] = $this->create($email, $city, $start, $end);
        $booking->save();
        return redirect("/");
    }

    /**
     * Show booking calendar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $values = $this->getCalendarValues($request);
        return view('index', [
            'time' => now(), 
            'booked' => $values['bookedDates'], 
            'week' => $values['week'], 
            'bookTime' => [
                'start' => $values['startHours'],
                'end' => $values['endHours']
            ],
            'cities' => $values['cities']->pluck('city')
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
