<section class="calendar">
    <i class="arrow fa fa-angle-double-left" onclick="changeWeek('left')"></i>
    <section class="calendar__wrapper" >
            @php
                function validateDate($day){
                    return !Carbon\Carbon::parse($day)->isWeekEnd() 
                    && Carbon\Carbon::parse($day)->timestamp >= Carbon\Carbon::now()->timestamp;
                }

                function validateHours(int $time, $from, $to){
                    return $time >= intval($from) and $time <= intval($to);
                }

                function formatDateToMS($date){
                    return Carbon\Carbon::parse($date)->timestamp;
                }

                function htmlSpace(string $string)
                {
                    return str_replace(' ', '%20', $string);
                }

                function isBooked($day, int $hour, array $bookedTimes){
                    if(count($bookedTimes) <= 0) return false;
                    $day = Carbon\Carbon::parse($day)->setTime($hour, 0);
                    foreach ($bookedTimes as $booking) {
                        $start = Carbon\Carbon::parse($booking['bookingDateStart']);
                        $end = Carbon\Carbon::parse($booking['bookingDateEnd']);
                        if($day->greaterThanOrEqualTo($start) && $day->lessThanOrEqualTo($end)){
                            return true;
                        }
                    }
                    return false;
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
                        @if(isBooked($day, $time, $booked))
                            <div class="calendar__row booked">
                        @else
                            <div class="calendar__row">
                        @endif
                            @if (validateDate($day))
                                @if ($time <= $bookTime['end'] - $bookingInterval && validateHours($time, $bookTime['start'], $bookTime['end']))
                                    <a class="calendar__link" href={{url('city/' . htmlSpace($city)."/?time=".formatDateToMS("{$day} {$time}:00:00")) }}>{{ $time }}:00</a>  
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