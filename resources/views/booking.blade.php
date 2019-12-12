@php
    $title = 'Booking';
@endphp
@component('templates.body', ['title' => $title])
    @php
        $email = isset(Auth::user()->email) ? Auth::user()->email : "";
        $firstName = isset(Auth::user()->firstName) ? Auth::user()->firstName : "";
        $lastName = isset(Auth::user()->lastName) ? Auth::user()->lastName : "";
        $time = isset($_GET["time"]) ? $_GET["time"] : 0;
    @endphp
    <script>
        function cancelBooking(url){
            fetch(url, {
                headers:{
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                method: 'DELETE',
            }).then((res) => {
                if(res.ok) location.replace('/');
                throw new Error(`${res.statusText} ${res.status}`);
            })
        }

        function rebook(url){
            const parseUrl = new URL(url);
            fetch(url, {
                headers:{
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                method: 'PUT',
                data:{
                    'time' : parseUrl.searchParams.get('time')
                }
            }).then((res) => {
                if(res.ok) location.replace('/');
                throw new Error(`${res.statusText} ${res.status}`);
            })
        }
    </script>
    <section class="booking">
    @if (!$isBooked)
        <form class="booking__form"action={{ url(Request::path()) }} method="POST">
            @csrf
            <input type="hidden" name="city" value={{ Request::path()}}>
            <input type="hidden" name="time" value={{ $time }}>
            <input type="text" name="email" placeholder="Email..." value={{ $email }}>
            <input type="text" name="firstName" placeholder="FirstName..." value={{ $firstName }}>
            <input type="text" name="lastName" placeholder="LastName..." value={{ $lastName }}>
            <input type="submit" value="Book now">
            <button type="button" onclick="location.replace('{{ url('/') }}')">Cancel</button>
        </form>
    @else
        <section class="booking__booked">
            @php
                $bookedDateStart =  \Carbon\Carbon::parse($booking->bookingDateStart);
                $bookedDateEnd = \Carbon\Carbon::parse($booking->bookingDateEnd);
                $bookingUpdate = \Carbon\Carbon::now()->timestamp(intval($time));
            @endphp
            <section class="booking__info">
                <span>You have an appointment {{ $bookedDateStart->toDateTimeString() }} -- {{ $bookedDateEnd->toDateTimeString() }}</span>
                <button type="button" class="button" onclick="cancelBooking('{{ url(Request::path()). '?' . 'time='. $bookedDateStart->timestamp }}')">Cancel Appointment</button>
            </section>
            <section class="booking__info">
                <span>Rebook appointment to this time {{ $bookingUpdate->toDateTimeString() }} -- {{ (clone $bookingUpdate)->addHours($bookingInterval)->toDateTimeString() }} ?</span>
                <button type="button" class="button" onclick="rebook('{{ url(Request::path()). '?' . 'time='. $bookingUpdate->timestamp }}')">Rebook Appointment</button>
            </section>
            <button type="button" class="button" onclick="location.replace('{{ url('/') }}')">Go back</button>
        </section>
    @endif
    </section>
    @include('templates.error')
    @include('templates.success')
@endcomponent
