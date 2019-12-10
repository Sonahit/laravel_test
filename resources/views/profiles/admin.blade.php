<?php $user = Auth::user(); ?>

@component('guards.admin')
    @component('profiles.user', ['appointments' => $appointments])
        @include('templates.grid', ['models' => $bookings->toArray(), 'title' => 'All Bookings'])
        <section class="company">
            @foreach ($cities as $attr)
                <span>Company available hours in city {{ $attr->city }}</span>
                <form class="company__form" action={{ url("/company/{$attr->city}") }} method="POST">
                    @csrf
                    <div class="company__input">
                        <span>Address</span>
                        <input type="text" name="address" value='{{ $attr->address }}' >
                    </div>
                    <div class="company__input">
                        <span>From</span>
                        <input type="text" name="startHours" value={{ $attr->startHours }} >
                    </div>
                    <div class="company__input">
                        <span>To</span>
                        <input type="text" name="endHours" value={{ $attr->endHours }} >
                    </div>
                    <input type="submit" value="Change">
                </form>
            @endforeach
        </section>
    @endcomponent
@endcomponent