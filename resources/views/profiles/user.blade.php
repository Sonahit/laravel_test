<section class="profile">
    Email: {{ Auth::user()->email }}
    First name: {{ Auth::user()->firstName }}
    Last name: {{ Auth::user()->lastName }}
    <form action="{{ url('users') }}" method="PUT">
        <label>
            Change first name
            <input type="text">
        </label>
        <label>
            Change last name
            <input type="text">
        </label>
    </form>
</section>