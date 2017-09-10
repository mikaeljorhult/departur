<ul class="time time-{{ $key }}">
    @foreach($events as $event)
        <li class="event">
            <span class="event-date">
                {{ $event->start_time->format('H:i') }}
                - {{ $event->end_time->format('H:i') }}
            </span>:

            <span class="event-title">{{ $event->title }}</span>

            @if(!empty($event->location))
                <div class="event-location">{{ $event->location }}</div>
            @endif

            @if(!empty($event->description))
                <div class="event-location">{{ $event->description }}</div>
            @endif
        </li>
    @endforeach
</ul>