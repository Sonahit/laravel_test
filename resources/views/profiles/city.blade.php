<section class="profile__company">
    @foreach ($cities as $attr)
        <h3>City {{ $attr->city }}</h3>
        <form class="company__form" action={{ url("/company/{$attr->city}") }} method="POST">
            @csrf
            <div class="company__input">
                <span>Address</span>
                <input type="text" name="address" value='{{ $attr->address }}' >
            </div>
            <h4><span>Available hours</span></h4>
            <div class="company__input">
                <span>From</span>
                <input type="text" name="startHours" value={{ $attr->startHours }} >
            </div>
            <div class="company__input">
                <span>To</span>
                <input type="text" name="endHours" value={{ $attr->endHours }}>
            </div>
            <div class="company__input">
                <h4>Booking Hours Interval</h4>
                <span>Interval is </span>
                <select class="company__select">
                    @for ($hour = 1; $hour <= 6; $hour++)
                        @if ($attr->bookingInterval === $hour)
                            <option class="company__select_option" selected>{{ $hour }} hours</option>
                        @else
                            <option class="company__select_option">{{ $hour }} hours</option>
                        @endif
                    @endfor
                </select>
                <input type="hidden" name="bookingInterval" value={{ $attr->bookingInterval }}>
            </div>
            <input type="submit" value="Change">
        </form>
    @endforeach
    <script>
        document.querySelectorAll(".company__select").forEach(select => {
            select.addEventListener('change', e => {
                const { target } = e;
                target.parentNode.children.bookingInterval.value = parseInt(target.value);
            })
        });
    </script>
</section>
