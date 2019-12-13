@component('mails.body')
    <section>
        <div>
            <span>Booked: {{ $booking->user->fullName()}}</span>
        </div>
        <div>
            <span>Email: {{ $booking->user->email}}</span>
        </div>
        <div>
            @php
                $start = \Carbon\Carbon::parse($booking->bookingDateStart);
                $end = \Carbon\Carbon::parse($booking->bookingDateEnd);
            @endphp
            <h3>Date</h3>
            <span> {{ $start->toDateString() }} </span>
            <h3>Time</h3>
            <span>From {{ $start->toTimeString() }} To {{ $end->toTimeString()}}</span>
            <h3>Timezone</h3>
            <span>{{$booking->place->timezone}}</span>
        </div>
    </section>
@endcomponent
