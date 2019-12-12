<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Utils\Traits\PrepareCalendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
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
                'end' => $values['endHours'],
            ],
            'bookingInterval' => $values['bookingInterval'],
            'cities' => $values['cities']->pluck('city'),
            'IS_REGISTRATION_OPEN' => Configuration::isRegistrationOpen()
        ]);
    }
}
