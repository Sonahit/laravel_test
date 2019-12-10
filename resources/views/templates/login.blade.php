@component('guards.admin')
    <section class="login">
        <a href="{{ url('users/profile') }}">Profile</a>
        @include('templates.logout')
    </section>
@endcomponent

@component('guards.web')
    <section class="login">
        <a href="{{ url('users/profile') }}">Profile</a>
        @include('templates.logout')
    </section>
@endcomponent

@component('guards.guest')
    <section class="login">
        <fieldset>
            <legend>Login</legend>
            <form action="{{ url('auth/login') }}" method="POST">
                @csrf
                <div>
                    <input type="text" name="email" placeholder="Email...">
                    <input type="password" name="password" placeholder="Password...">
                </div>
                <div>
                    <label for="check_remember">Remember</label>
                    <input id="check_remember" type="checkbox" name="remember">
                </div>  
                <input type="submit">
            </form>
        </fieldset>
        @include('templates.error')
        @include('templates.success')
        @if (config('auth.REGISTRATION_IS_OPEN'))
            <a href="auth/register">Create new account</a>  
        @endif
    </section>
@endcomponent
