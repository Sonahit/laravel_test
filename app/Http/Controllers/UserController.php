<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $startWeek = now()->startOfWeek()->setTime(Booking::START, 0);
        $endWeek = now()->endOfWeek()->setTime(Booking::END, 0);
        $bookedDates = Booking::select('*')->bookedBetween($startWeek, $endWeek)->get();
        $week = collect(now()->getDays())->map(function($_, $index) use($startWeek){
            return Carbon::createFromDate(now()->year, now()->month, $startWeek->day + $index)->setTime(0, 0)->toDateString();
        });
        return view('index', [
            'time' => now(), 
            'booked' => $bookedDates, 
            'week' => $week, 
            'bookTime' => [
                'start' => Booking::START,
                'end' => Booking::END
            ]
        ]);
    }
}
