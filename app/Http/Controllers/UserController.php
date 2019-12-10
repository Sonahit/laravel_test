<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Place;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        if (is_null($user)) {
            return redirect('/');
        }

        $appointments = Booking::with('place')
            ->whereHas('user', function (Builder $q) use ($user) {
                $q->where('email', $user->email);
            })
            ->whereDate('bookingDateStart', '>', now()->toDateString())
            ->get()
            ->map(function ($booking) {
                return [
                    'Id' => $booking->id,
                    'City' => $booking->place->city,
                    'Start of appointment' => $booking->bookingDateStart,
                    'End of appointment' => $booking->bookingDateEnd,
                ];
            });
        if ($user->isAdmin) {
            $places = Place::select(Place::ROWS)->get();
            $bookings = Booking::select()
                ->with(['place', 'user'])
                ->whereDate('bookingDateStart', '>=', now()->toDateString())
                ->get()->map(function ($booking) {
                    return [
                        'Id' => $booking->id,
                        'City' => $booking->place->city,
                        'User' => $booking->user->email,
                        'Start of appointment' => $booking->bookingDateStart,
                        'End of appointment' => $booking->bookingDateEnd,
                    ];
                });
            return view('profile', ['cities' => $places, 'bookings' => $bookings, 'appointments' => $appointments]);
        }
        return view('profile', ['appointments' => $appointments]);
    }
}
