@component('guards.admin')
    <section class="profile__welcome">
        <a class="button" href="{{ url('users/profile') }}">Profile</a>
        @isset($links)
            {{ $links }}
        @endisset
        @include('templates.logout')
    </section>
@endcomponent

@component('guards.web')
    <section class="profile__welcome">
        <a class="button" href="{{ url('users/profile') }}">Profile</a>
        @isset($links)
            {{ $links }}
        @endisset
        @include('templates.logout')
    </section>
@endcomponent

@component('guards.guest')
    <section class="login">
        <form id="login__form" class="login__form" action="{{ url('auth/login') }}" method="POST">
            @csrf
            <div class="login__input">
                <input type="text" name="email" placeholder="Email...">
                <input type="password" name="password" placeholder="Password...">
            </div>
            <div class="login__input">
                <label for="check_remember">Remember</label>
                <input id="check_remember" type="checkbox" name="remember">
            </div>  
            
        </form>
        <div class="login__actions">
            <input form="login__form" class="button" type="submit" value="Sign In">
            @isset($IS_REGISTRATION_OPEN)
                @if ($IS_REGISTRATION_OPEN)
                    <a class="button" href="auth/register">Sign Up</a>  
                @endif  
            @endisset
        </div>
        @include('templates.error')
        @include('templates.success')
        
    </section>
@endcomponent
