<li class="day day-{{ $key }}
    {{ $events->first()->start_time->isToday() ? 'day__today' : '' }}
    {{ $events->first()->start_time->endOfDay()->isPast() ? 'day__past' : '' }}
    {{ $events->first()->start_time->startOfDay()->isFuture() ? 'day__future' : '' }}
">
    <h4 class="day-title">{{ $key }}</h4>

    @foreach($events->groupBy(function ($item, $key) {
        return $item->start_time->format('Hi');
    }) as $key => $events)
        @include('schedules._time', [
            'events' => $events,
            'sortOrder' => $sortOrder
        ])
    @endforeach
</li>