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
        $name = isset(Auth::user()->name) ? Auth::user()->name : "";
        $dateFrom = '';
        $dateTo = '';
    @endphp
    <form action='/city/booking' method="POST">
        @csrf
        <input type="hidden" name="city" value={{ $city }}>
        <input type="text" name="email" placeholder="Email..." value={{ $email }}>
        <input type="text" name="name" placeholder="Name..." value={{ $name }}>
        <input type="text" name="dateFrom" placeholder="Date from..." readonly value={{ $dateFrom }}>
        <input type="text" name="dateTo" placeholder="Date to..." readonly value={{ $dateTo }}>
        <input type="submit" value="Book">
    </form>
</body>
</html>