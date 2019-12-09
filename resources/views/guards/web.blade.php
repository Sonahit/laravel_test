@php
   $user = Auth::user();
@endphp

@if (isset($user) && !$user->isAdmin)
    {{ $slot }}
@endif