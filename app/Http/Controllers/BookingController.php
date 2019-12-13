<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Booking;
use App\Models\Place;
use App\Models\User;
use App\Utils\Helpers\RequestHelper;
use App\Utils\Traits\PrepareCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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
        $user = User::firstOrNew(['email' => $email]);
        $user->save();
        $userId = $user->id;
        $placeId = Place::where('city', $city)->first()->id;
        $booking = Booking::where([
            'userId' => $userId,
            'placeId' => $placeId,
        ])->whereDate('bookingDateStart', $from->toDate())->first();
        if (is_null($booking)) {
            $booking = Booking::create([
                'userId' => $userId,
                'placeId' => $placeId,
                'bookingDateStart' => $from->toDateTimeString(),
                'bookingDateEnd' => $to->toDateTimeString(),
            ]);
        } else {
            $booking->bookingDateStart = $from->toDateTimeString();
            $booking->bookingDateEnd = $to->toDateTimeString();
        }
        $booking->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BookRequst  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request, $city)
    {
        $query = $request->only('time', 'firstName', 'lastName', 'email');
        if (is_null($query['email'])) {
            return response()->json(['message' => 'Email cannot be empty']);
        } elseif (is_null($query['time'])) {
            return response()->json(['message' => 'Date cannot be empty']);
        }
        $email = $query['email'];
        $place = Place::where('city', $city)->first();
        $start = Carbon::createFromTimestamp(intval($query['time']));
        $end = Carbon::createFromTimestamp(intval($query['time']))->addHours($place->bookingInterval);
        $this->create($email, $city, $start, $end);
        return redirect("/");
    }

    public function show(Request $request, $city)
    {
        $query = RequestHelper::queryToArray($request, ['time']);
        $place = Place::where('city', $city)->first();
        $user = Auth::user();
        $isBooked = false;
        $booking = null;
        if (!is_null($user)) {
            $start = Carbon::createFromTimestamp(intval($query['time']));
            $whereAttr = [
                'userId' => $user->id,
                'placeId' => $place->id,
            ];
            $booking = Booking::where($whereAttr)
                ->whereDate('bookingDateStart', $start->toDate())->first();
            if (!is_null($booking)) {
                $isBooked = true;
            }
        }
        $dateTime = $query['time'];
        if (is_null($city) || is_null($dateTime)) {
            return redirect('/');
        }
        return view('booking', ['city' => $city, 'dateTime' => $dateTime, 'isBooked' => $isBooked, 'booking' => $booking, 'bookingInterval' => $place->bookingInterval ]);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $city)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Not allowed'], 401);
        }
        $date = now()->timestamp($request->query('time'));
        $place = Place::where('city', $city)->first();
        $user = Auth::user();
        $booking = Booking::where([
            'userId' => $user->id,
            'placeId' => $place->id,
        ])->whereDate('bookingDateStart', $date->toDateString())->first();
        $booking->bookingDateStart = $date->toDateTimeString();
        $booking->bookingDateEnd = (clone $date)->addHours($place->bookingInterval)->toDateTimeString();
        $booking->save();
        return response()->json(['message' => 'Successfully updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $city)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Not allowed'], 401);
        }
        $time = request()->query('time');
        $user = Auth::user();
        $date = now()->timestamp($time)->toDateTimeString();
        $booking = Booking::where('bookingDateStart', $date)
            ->whereHas('place')
            ->whereHas('user', function ($q) use ($user) {
                $q->where('id', $user->id);
            })
            ->first();
        if (is_null($booking)) {
            return response()->json(['message' => 'Successfully deleted instance'], 200);
        }
        $booking->delete();
        return response()->json(['message' => 'Successfully deleted instance'], 200);
    }
}
