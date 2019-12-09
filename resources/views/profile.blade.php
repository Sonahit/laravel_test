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
    @include('templates.login')
    <a href="/">Home</a>
    @auth('web_admin')
        @include('profiles.admin')
    @endauth 
    @auth('web')
        @include('profiles.user')
    @endauth
</body>
</html>