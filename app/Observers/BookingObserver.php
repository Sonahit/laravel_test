<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Link;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\UserToNotify;

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
            'notification_name' => 'booking.created',
        ]);
        $test = NotificationUser::create([
            'userId' => $booking->user->id,
            'notificationId' => $notif->id
        ]);
        $link = Link::firstOrNew([
            'userId' => $booking->user->id,
            'bookingId' => $booking->id,
            'deleteLink' => url('/users/link/'.generateToken()),
            'updateLink' => url('/users/link/'.generateToken()),
            'isActive' => 1,
            'expiresAt' => now()->parse($booking->bookingDateEnd)->timestamp
        ]);
        $asd = now();
        // UserToNotify::created($booking);
    }

    /**
     * Handle the booking "updated" event.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function updated(Booking $booking)
    {
        //
    }

    /**
     * Handle the booking "deleted" event.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function deleted(Booking $booking)
    {
        //
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
