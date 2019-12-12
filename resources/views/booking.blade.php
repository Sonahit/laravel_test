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
                $bookedDate =  \Carbon\Carbon::now()->timestamp(intval($time));
            @endphp
            <span>You have booked at {{ $bookedDate->toDateTimeString() }} to {{ $bookedDate->addHours(2)->toDateTimeString() }}</span>
            <button type="button" onclick="cancelBooking('{{ url(Request::path()). '?' . 'time='. $time }}')">Cancel Booking</button>
            <button type="button" onclick="location.replace('{{ url('/') }}')">Go back</button>
        </section>
        @endif
    </section>
    @include('templates.error')
    @include('templates.success')
@endcomponent
