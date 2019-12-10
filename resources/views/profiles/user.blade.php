<?php $user = Auth::user(); ?>
@if ($user)
    <section class="profile">
        @include('templates.error')
        @include('templates.success')
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
            <section class="profile__form">
                <form action="{{ url('/users/user') }}" method="POST">
                    @csrf
                    <div class="profile__form_element">
                        <label>
                            First name
                        </label>
                        <input type="text"name="firstName" value={{ $user->firstName }}>
                    </div>
                    <div class="profile__form_element">
                        <label>
                            Last name
                        </label>
                        <input type="text" name="lastName" value={{ $user->lastName }}>
                    </div>
                    <input type="submit" value="Change">
                </form>
            </section>
            <section class="profile__appointments">
                @include('templates.grid', ['models' => $appointments, 'title' => 'Your appointments'])
            </section>
            @if(isset($slot))
                {{ $slot }}
            @endif
    </section>
@else 
    <a href="/">Go back</a>
@endif
