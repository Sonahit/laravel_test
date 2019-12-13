<?php

namespace App\Utils\Helpers;

use App\Models\Booking;
use Carbon\CarbonTimeZone;
use Exception;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class EventHelper
{

    public static function createEvent(
        Booking $booking,
        Collection $attendees,
        Carbon $start,
        Carbon $end,
        CarbonTimeZone $timezone
    ) {
        $event = new Google_Service_Calendar_Event();
        $time = new Google_Service_Calendar_EventDateTime();
        $fullNames = $attendees->reduce(function ($acc, $user) {
            $acc->push("{$user->fullName()}");
            return $acc;
        }, collect())->toArray();
        $place = $booking->place;
        $user = $booking->user;
        $event->setAttendees($fullNames);
        $startTime =(clone $time);
        $startTime->setDateTime($start->toDateTimeLocalString());
        $startTime->setTimeZone(strval($timezone));
        $endTime = (clone $time);
        $endTime->setDateTime($end->toDateTimeLocalString());
        $endTime->setTimeZone(strval($timezone));
        $event->setStart($startTime);
        $event->setEnd($endTime);
        $event->setSummary("Booked: {$user->fullName()}");
        $event->setDescription("{$user->fullName()} ({$user->email}) have booked at {$place->city}
        from {$start->toDateTimeString()} to {$end->toDateTimeString()}");
        return $event;
    }

    public static function sendEvent($event)
    {
        $client = getGoogleClient();
        $service = new Google_Service_Calendar($client);
        $calendarId = env('GOOGLE_CALENDAR_ID');
        $service->events->insert($calendarId, $event);
        Log::info('Successfully added to google calendar');
    }
}
