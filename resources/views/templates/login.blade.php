@auth('web_admin')
    web_admin
    <section class="login">
        <a href="{{ url('users/profile') }}">Profile</a>
        @include('templates.logout')
    </section>
@endauth 

@auth('web')
    web
    <section class="login">
        <a href="{{ url('users/profile') }}">Profile</a>
        @include('templates.logout')
    </section>
@endauth

@guest
    <section class="login">
        <fieldset>
                <legend>Login</legend>
                <form action="auth/login" method="POST">
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
            <a href="auth/register">Create new account</a>
    </section>
    
@endauth
