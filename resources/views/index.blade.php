@php
    $title = 'Booking';
@endphp

@component('templates.body', ['title' => $title])
    <header>
        <section class="main__header">
            @include('templates.login', ['IS_REGISTRATION_OPEN' => $IS_REGISTRATION_OPEN])
        </section>
    </header>
    <main>
        <section class="main__content">
            <select class="main__cities select_cities">
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
        </section>
    </main>
    <script src="./js/app.js"></script>
@endcomponent
    
