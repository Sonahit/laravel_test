<?php

namespace App\Observers;

use App\Mail\BookingCreated;
use App\Mail\BookingDeleted;
use App\Mail\BookingUpdated;
use App\Models\Booking;
use App\Models\Link;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\UserToNotify;
use App\Utils\Helpers\EventHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class BookingObserver
{
    /**
     * Handle the booking "created" event.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function created(Booking $booking)
    {
        $booking->load(['user', 'place']);
        $notif = Notification::firstOrNew([
            'name' => 'booking.created',
        ]);
        $notif->save();
        $notification = NotificationUser::firstOrNew([
            'userId' => $booking->user->id,
            'notificationId' => $notif->id
        ]);
        $notification->save();
        $link = Link::firstOrNew([
            'userId' => $booking->user->id,
            'bookingId' => $booking->id,
            'deleteLink' => generateToken(),
            'updateLink' => generateToken(),
            'isActive' => 1,
            'expiresAt' => now()->parse($booking->bookingDateEnd)->timestamp
        ]);
        $link->save();
        $stockHolders = UserToNotify::with('user')->get();
        foreach ($stockHolders as $index => $stockHolder) {
            Mail::to($stockHolder->user->email)->later(now()->addMinutes($index), new BookingCreated($booking));
        }
        $startTime = Carbon::parse($booking->bookingDateStart);
        $endTime = Carbon::parse($booking->bookingDateEnd);
        $timezone = now()->setTimeZone($booking->place->timezone)->timezone;
        $event = EventHelper::createEvent($booking, collect([$booking->user]), $startTime, $endTime, $timezone);
        EventHelper::sendEvent($event);
        Mail::to($booking->user->email)->later(now()->addMinute(), new BookingCreated($booking));
    }

    /**
     * Handle the booking "updated" event.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function updated(Booking $booking)
    {
        $booking->load(['user', 'place']);
        $stockHolders = UserToNotify::with('user')->get();
        foreach ($stockHolders as $index => $stockHolder) {
            Mail::to($stockHolder->user->email)->later(now()->addMinutes($index), new BookingUpdated($booking));
        }
        Mail::to($booking->user->email)->later(now()->addMinute(), new BookingUpdated($booking));
    }

    /**
     * Handle the booking "deleted" event.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function deleted(Booking $booking)
    {
        $booking->load(['user', 'place']);
        $stockHolders = UserToNotify::with('user')->get();
        foreach ($stockHolders as $index => $stockHolder) {
            Mail::to($stockHolder->user->email)->later(now()->addMinutes($index), new BookingDeleted($booking));
        }
        Mail::to($booking->user->email)->later(now()->addMinute(), new BookingDeleted($booking));
    }

    /**
     * Handle the booking "restored" event.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function restored(Booking $booking)
    {
        //
    }

    /**
     * Handle the booking "force deleted" event.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function forceDeleted(Booking $booking)
    {
        //
    }
}
