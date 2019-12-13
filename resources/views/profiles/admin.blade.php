<?php $user = Auth::user(); ?>

@component('guards.admin')
    @component('profiles.user', ['appointments' => $appointments])
        @include('profiles.options', ['configs' => $configs])
        @include('profiles.stakeHolders', ['stakeHolders' => $stakeHolders])
        @include('templates.grid', ['models' => $bookings->toArray(), 'title' => 'All Bookings'])
        @include('profiles.city', ['cities' => $cities])
    @endcomponent
@endcomponent
