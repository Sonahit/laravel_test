<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Booking</title>
</head>
<body>   
    <main>
        @include('templates.login')
        <select class="select_cities">
            @foreach ($cities as $city)
                <option value={{ $city }}>{{ $city }}</option>
            @endforeach
        </select>
        @include('templates.calendar', [
            'week' => $week,
            'booked' => $booked,
            'bookTime' => $bookTime,
            'city' => $cities[0]
        ])
    </main>
    <footer>
        footer hello
    </footer>
    <script src="./js/app.js"></script>
</body>
</html>
