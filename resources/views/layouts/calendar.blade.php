<section class="calendar">
    <section class="calendar__head">
        @foreach ($week as $day)
            <div class="calendar__head__row" day={{ $day }}>
                {{ Carbon\Carbon::parse($day)->locale('ru_RU')->isoformat('dd D.M.Y') }}
            </div>
        @endforeach
    </section>
    <section class="calendar__body">
        @for ($time = $bookTime['start']; $time <= $bookTime['end']; $time++)
            <div class="calendar__body__row">{{ $time }}:00</div>
        @endfor
    </section>
</section>