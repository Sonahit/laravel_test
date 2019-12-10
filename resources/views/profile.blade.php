@php
    $email = isset(Auth::user()->email) ? Auth::user()->email : "User";
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile | {{ $email }}</title>
</head>
<body>
    <main>
        <aside>
            @include('templates.login')
            <a href="/">Home</a>
        </aside>
        @component('guards.admin')
            @include('profiles.admin', [
                'cities' => $cities,
                'bookings' => $bookings,
                'appointments' => $appointments
            ])
        @endcomponent
        @component('guards.web')
            @include('profiles.user', ['appointments' => $appointments])
        @endcomponent
    </main>
</body>
</html>