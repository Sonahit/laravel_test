@php
    $title = 'Register';
@endphp
@component('templates.body', compact($title))
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
@endcomponent
