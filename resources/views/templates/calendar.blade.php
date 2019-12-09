<section class="calendar">
    <i class="arrow fa fa-angle-double-left" onclick="changeWeek('left')"></i>
    <section class="calendar__wrapper" >
            @php
                function validateDate($day){
                    return !Carbon\Carbon::parse($day)->isWeekEnd() && Carbon\Carbon::parse($day) >= Carbon\Carbon::now();
                }

                function validateHours($time){
                    return $time >= 8 and $time <= 12;
                }

                function formatDateToMS($date){
                    return Carbon\Carbon::parse($date)->timestamp;
                }

                function htmlSpace($string)
                {
                    return str_replace(' ', '%20', $string);
                }
            @endphp
            @foreach ($week as $day)
            <section class="calendar__content">
                <div 
                    class={{ validateDate($day)
                            ? "calendar__header" 
                            : "calendar__header disabled" 
                    }} 
                    day={{ $day }}
                >
                    {{ Carbon\Carbon::parse($day)->locale('ru_RU')->isoformat('dd') }}
                    <br>
                    {{ Carbon\Carbon::parse($day)->locale('ru_RU')->isoformat('D.M.Y') }}
                </div>
                <section class="calendar__rows">
                    @for ($time = $bookTime['start']; $time <= $bookTime['end']; $time++)
                        <div class="calendar__row">
                            @if (validateDate($day))
                                @if (validateHours($time))
                                    <a class="calendar__link" href={{url(htmlSpace($city)."/?time=".formatDateToMS("{$day} {$time}:00:00")) }}>{{ $time }}:00</a>  
                                @else 
                                    <span class="calendar__link disabled">{{ $time }}:00</span>
                                @endif
                            @else
                                <span>{{ $time }}:00</span>
                            @endif
                        </div>
                    @endfor
                </section>
            </section>
            @endforeach
        </section>
    <i class="arrow fa fa-angle-double-right" onclick="changeWeek('right')"></i>
</section>