@php
    $user = Auth::user();
    $email = isset($user->email) ? $user->email : "User";
    $title = "Profile | {$email}";
@endphp
@component('templates.body', ['title' => $title])
    <main>
        <aside>
            @include('templates.login')
            <a href="/">Home</a>
        </aside>
        @if($user->isAdmin)
            @component('guards.admin')
                @include('profiles.admin', [
                    'cities' => $cities,
                    'bookings' => $bookings,
                    'appointments' => $appointments,
                    'conifgs' => $configs
                ])
            @endcomponent
        @endif
        @component('guards.web')
            @include('profiles.user', ['appointments' => $appointments])
        @endcomponent
    </main>
@endcomponent