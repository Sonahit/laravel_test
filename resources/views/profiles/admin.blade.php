<?php $user = Auth::user(); ?>

@component('guards.admin')
    <section class="profile">
        <section class="profile__information">
            <div class="profile__element">
                <fieldset>
                    <legend>Email</legend>
                    <label>{{ $user->email }}</label>
                </fieldset>
            </div>
            <div class="profile__element">
                <fieldset>
                    <legend>First name</legend>
                    <label>{{ $user->firstName }}</label>
                </fieldset>
            </div>
            <div class="profile__element">
                <fieldset>
                    <legend>Last name</legend>
                    <label>{{ $user->lastName }}</label>
                </fieldset>
            </div>
        </section>
        <form action="{{ url('users') }}" method="PUT">
            <label>
                First name
            </label>
            <input type="text"name="firstName" value={{ $user->firstName }}>
            <label>
                Last name
            </label>
        <input type="text" name="lastName" value={{ $user->lastName }}>
            <input type="submit" value="Apply">
        </form>
        <div>
            Companies start hours is {{ config('app.startHours') }} hours
        </div>
        <div>
            Companies end hours is {{ config('app.endHours') }} hours
        </div>
</section>
@endcomponent