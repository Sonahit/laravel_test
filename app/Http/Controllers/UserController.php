<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Configuration;
use App\Models\Place;
use App\Models\UserToNotify;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request)
    {
        $attr = $request->only('firstName', 'lastName');
        $user = Auth::user();
        $user->firstName = $attr['firstName'];
        $user->lastName = $attr['lastName'];
        $user->save();
        return redirect()->back()->with('success', 'Successfully updated your data');
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
                        'User' => isset($booking->user) ? $booking->user->email : "NO EMAIL",
                        'Start of appointment' => $booking->bookingDateStart,
                        'End of appointment' => $booking->bookingDateEnd,
                    ];
                });
            $configs = Configuration::getConfigs();
            $stakeHolders = UserToNotify::with('userInfo')->get();
            return view('profile', [
                'cities' => $places,
                'bookings' => $bookings,
                'appointments' => $appointments,
                'configs' => $configs,
                'stakeHolders' => $stakeHolders
            ]);
        }
        return view('profile', ['appointments' => $appointments]);
    }
}
