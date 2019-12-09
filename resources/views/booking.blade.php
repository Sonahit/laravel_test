<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booking</title>
</head>
<body>
    @php
        $email = isset(Auth::user()->email) ? Auth::user()->email : "";
        $firstName = isset(Auth::user()->firstName) ? Auth::user()->firstName : "";
        $lastName = isset(Auth::user()->lastName) ? Auth::user()->lastName : "";
        $time = isset($_GET["time"]) ? $_GET["time"] : 0;
    @endphp
    <script>
        function cancelBooking(){
            location.replace('{{ url('/') }}');
        }
    </script>
    <form action={{ url(Request::path()) }} method="POST">
        @csrf
        <input type="hidden" name="city" value={{ Request::path()}}>
        <input type="hidden" name="time" value={{ $time }}>
        <input type="text" name="email" placeholder="Email..." value={{ $email }}>
        <input type="text" name="firstName" placeholder="FirstName..." value={{ $firstName }}>
        <input type="text" name="lastName" placeholder="LastName..." value={{ $lastName }}>
        <input type="submit" value="Book now">
        <button type="button" onclick="cancelBooking()">Cancel</button>
    </form>
    @include('templates.error')
    @include('templates.success')
</body>
</html>