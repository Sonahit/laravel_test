<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Utils\Helpers\RequestHelper;
use Illuminate\Http\Request;
use App\Utils\Traits\PrepareCalendar;

class TemplateController extends Controller
{
    use PrepareCalendar;

    public function sendCalendar(Request $request)
    {
        $query = RequestHelper::queryToArray($request, ['date', 'city', 'week']);
        $cities=Place::all();
        $city = is_null($query['city']) ? $cities->first()->city : $query['city'];
        if($cities->contains('city', $city)){
            $startHours = $cities->firstWhere('city', $city)->startHours;
            $endHours = $cities->firstWhere('city', $city)->endHours;
        } else {
            $startHours = config('app.startHours');
            $endHours = config('app.endHours');;
        }
        $values = $this->getCalendarValues($request);
        $html = view('templates.calendar', [
            'booked' => $values['bookedDates'], 
            'week' => $values['week'], 
            'bookTime' => [
                'start' => $startHours,
                'end' => $endHours
            ],
            'city' => $city
        ])->render();

        return response()->json(['html' => $html]);
    }
}
