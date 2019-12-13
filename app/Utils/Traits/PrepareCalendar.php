<?php

namespace App\Utils\Traits;

use App\Models\Booking;
use App\Models\Place;
use App\Utils\Helpers\RequestHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
trait PrepareCalendar
{
    public function getCalendarValues(Request $request)
    {
        $query = RequestHelper::queryToArray($request, ['date', 'city', 'week']);
        $places = Place::all();
        $place = is_null($query['city']) ? $places->first() : $places->firstWhere('city', $query['city']);
        $week = is_null($query['week']) ? 0 : intval($query['week']);
        if ($places->contains('city', $place->city)) {
            $startHours = $places->firstWhere('city', $place->city)->startHours;
            $endHours = $places->firstWhere('city', $place->city)->endHours;
        } else {
            $startHours = $place->startHours;
            $endHours = $place->endHours;
        }
        $date = is_null($query['date']) ? now() : Carbon::createFromFormat('Y-m-d', $query['date']);
        $startWeek = (clone $date)->startOfWeek(6)->addWeeks($week)->setTime($startHours, 0);
        $endWeek = (clone $startWeek)->addDays(8)->setTime($endHours, 0);
        $user = Auth::user();
        $week = collect(now()->getDays())->map(function ($_, $index) use ($startWeek) {
            return Carbon::createFromDate(
                $startWeek->year,
                $startWeek->month,
                $startWeek->day + $index
            )
            ->setTime(0, 0)
            ->toDateString();
        });
        $bookingInterval = $place->bookingInterval;
        $bookedDates = Booking::select('bookingDateStart', 'bookingDateEnd')
            ->bookedBetween($startWeek, $endWeek)
            ->whereHas('place', function (Builder $sub) use ($place) {
                    $sub->where('city', $place->city);
            })->get()->toArray();
        return [
            'bookedDates' => $bookedDates,
            'week' => $week,
            'startHours' => $startHours,
            'endHours' => $endHours,
            'cities' => $places,
            'city' => $place,
            'bookingInterval' => $bookingInterval
        ];
    }
}
