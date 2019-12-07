@auth('web')
    <section class="login">
        <form action="auth/logout" method="GET">
            <input type="submit" value="Logout">
        </form>
    </section>
@endauth

@auth('admin')
    <section class="login">
        <form action="auth/logout" method="GET">
            <input type="submit" value="Logout">
        </form>
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
                        <input id="check_remember" type="checkbox">
                    </div>  
                    <input type="submit">
                </form>
            </fieldset>
    </section>
    <a href="/register">Create new account</a>
@endauth
