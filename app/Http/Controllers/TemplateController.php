<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Place;
use App\Utils\Helpers\RequestHelper;
use App\Utils\Traits\PrepareCalendar;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    use PrepareCalendar;

    public function sendCalendar(Request $request)
    {
        $values = $this->getCalendarValues($request);
        $html = view('templates.calendar', [
            'booked' => $values['bookedDates'],
            'week' => $values['week'],
            'bookTime' => [
                'start' => $values['startHours'],
                'end' => $values['endHours'],
            ],
            'city' => $values['city'],
        ])->render();

        return response()->json(['html' => $html]);
    }
}
