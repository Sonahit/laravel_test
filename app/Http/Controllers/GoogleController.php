<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_Events;

class GoogleController extends Controller
{
    public function index(Request $request)
    {
        $client = getGoogleClient();
        $service = new Google_Service_Calendar($client);
        $calendarId = env('GOOGLE_CALENDAR_ID');
        $event = new Google_Service_Calendar_Event();
        $time = new Google_Service_Calendar_EventDateTime();
        $event->setAttendees('Ivan Sadykov');
        $start =(clone $time);
        $start->setDateTime(now()->toDateTimeLocalString());
        $start->setTimeZone(strval(now()->timezone));
        $end = (clone $time);
        $end->setDateTime(now()->addHours(2)->toDateTimeLocalString());
        $end->setTimeZone(strval(now()->timezone));
        $event->setStart($start);
        $event->setEnd($end);
        $event->setSummary('Booking');
        $event->setDescription('Booking');
        $resp = $service->events->insert($calendarId, $event);
        return response()->json($resp);
    }
}
