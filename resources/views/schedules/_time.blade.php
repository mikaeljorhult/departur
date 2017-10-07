<ul class="row time time-{{ $key }}">
    @foreach($events as $event)
        <li class="column event {{ !empty($event->description) ? 'event__truncated' : '' }}">
            <span class="event-date">
                {{ $event->start_time->format('H:i') }}
                - {{ $event->end_time->format('H:i') }}:
            </span>

            <span class="event-title">{{ $event->title }}</span>

            @if(!empty($event->location))
                <div class="event-location">{{ $event->location }}</div>
            @endif

            @if(!empty($event->description))
                <div class="event-description">{!! nl2br(e($event->description)) !!}</div>
                <a href="#" class="event-readmore"></a>
            @endif
        </li>
    @endforeach
</ul>