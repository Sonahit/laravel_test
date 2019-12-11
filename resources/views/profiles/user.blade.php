<?php $user = Auth::user(); ?>
@if ($user)
    <section class="profile">
        @include('templates.error')
        @include('templates.success')
        <section class="profile__information">
            <h2>Information</h2>
                <div class="profile__element">
                        <h3>Email</h3>
                        <label>{{ $user->email }}</label>
                </div>
                <div class="profile__element">
                        <h3>First name</h3>
                        <label>{{ $user->firstName }}</label>
                </div>
                <div class="profile__element">
                        <h3>Last name</h3>
                        <label>{{ $user->lastName }}</label>
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
