<li class="day day-{{ $key }}
    {{ $events->first()->start_time->isToday() ? 'day__today' : '' }}
    {{ $events->first()->start_time->endOfDay()->isPast() ? 'day__past' : '' }}
    {{ $events->first()->start_time->startOfDay()->isFuture() ? 'day__future' : '' }}
">
    <h4 class="day-title">{{ $key }}</h4>

    @each('schedules._time', $events->groupBy(function ($item, $key) {
        return $item->start_time->format('Hi');
    }), 'events')
</li>