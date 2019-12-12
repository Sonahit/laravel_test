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
            'notification_name' => 'booking.created',
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
        Mail::to($booking->user->email)->send(new BookingCreated($booking));
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
        Mail::to($booking->user->email)->send(new BookingUpdated($booking));
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
        Mail::to($booking->user->email)->send(new BookingDeleted($booking));
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
