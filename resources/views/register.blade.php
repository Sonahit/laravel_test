<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
    <fieldset>
        <legend>Register</legend>
        <form id="register" action="/auth/register" method="POST">
            @csrf
            <input type="text" name="email" placeholder="Email...">
            <input type="text" name="firstName" placeholder="FirstName...">
            <input type="text" name="lastName" placeholder="LastName...">
            <input type="password" name="password" placeholder="Password...">
            <input type="password" name="password_confirmation" placeholder="Confirm password">
            <input type="submit">
        </form>
    </fieldset>
    @include('templates.error')
    <a href='/'>Go back</a>
</body>
</html>
