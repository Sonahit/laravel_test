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
    public function getCalendarValues(Request $request){
        $query = RequestHelper::queryToArray($request, ['date', 'city', 'week']);
        $city = $query['city'];
        $cities = Place::all();
        $week = is_null($query['week']) ? 0 : intval($query['week']);
        if($cities->contains('city', $city)){
            $startHours = $cities->firstWhere('city', $city)->startHours;
            $endHours = $cities->firstWhere('city', $city)->startHours;
        } else {
            $startHours = config('app.startHours');
            $endHours = config('app.endHours');;
        }
        $date = is_null($query['date']) ? now() : Carbon::createFromFormat('Y-m-d', $query['date']);
        $startWeek = (clone $date)->addWeeks($week)->previousWeekendDay()->addDays(-1)->setTime($startHours, 0);
        $endWeek = (clone $date)->endOfWeek(5)->setTime($endHours, 0);
        $user = Auth::user();
        $bookedDates = Booking::select('bookingDateStart', 'bookingDateEnd')
            ->bookedBetween($startWeek, $endWeek)
            ->when(!is_null($user), function(Builder $q) use($user){
                $q->where('userId', $user->id);
            })
            ->when(!is_null($city), function(Builder $q) use($city){
                $q->whereHas('places', function(Builder $sub) use($city){
                    $sub->where('city', $city);
                });
            }, function(Builder $q) use($cities){
                $q->whereHas('places', function(Builder $sub) use($cities){
                    $sub->where('city', $cities->first()->city);
                });
            })->get()->toArray();
        $week = collect(now()->getDays())->map(function($_, $index) use($startWeek){
            return Carbon::createFromDate($startWeek->year, $startWeek->month, $startWeek->day + $index)->setTime(0, 0)->toDateString();
        });
        return [
            'bookedDates' => $bookedDates,
            'week' => $week,
            'startHours' => $startHours,
            'endHours' => $endHours,
            'cities' => $cities,
            'city' => $city
        ];
    }
}
