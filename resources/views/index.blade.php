@php
    $stylesUrl = ["css/app.css", "css/calendar.css"];
    $title = 'Booking';
@endphp

@component('templates.body', ['stylesUrl' => $stylesUrl, 'title' => $title])
    <main>
        @include('templates.login', ['IS_REGISTRATION_OPEN' => $IS_REGISTRATION_OPEN])
        <select class="select_cities">
            @foreach ($cities as $city)
                <option value={{ $city }}>{{ $city }}</option>
            @endforeach
        </select>
        @include('templates.calendar', [
            'week' => $week,
            'booked' => $booked,
            'bookTime' => $bookTime,
            'city' => $cities[0],
            'bookingInterval' => $bookingInterval
        ])
    </main>
    <script src="./js/app.js"></script>
@endcomponent
    
