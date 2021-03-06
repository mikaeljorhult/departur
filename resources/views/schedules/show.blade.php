@extends('layouts.app')

@section('title', $schedule->name)

@section('content')
    <h1>{{ $schedule->name }}</h1>

    @if($schedule->calendars->count() > 0)
        <ul class="calendar-toggle-list">
            @foreach($schedule->calendars as $calendar)
                <li class="calendar-toggle calendar-toggle__color{{ $sortOrder[$calendar->id] }}">{{ $calendar->name }}</li>
            @endforeach
        </ul>
    @endif

    <label for="hide-past-toggle" class="hide-past-toggle">
        <input type="checkbox" name="hide-past-toggle" id="hide-past-toggle" />Hide past dates
    </label>

    <section class="schedule">
        @if($schedule->events->count() > 0)
            <ul class="list-days">
                @foreach($schedule->events->groupBy(function ($item) {
                    return $item->start_time->format('Y-m-d');
                }) as $key => $events)
                    @include('schedules._day', [
                        'events' => $events,
                        'sortOrder' => $sortOrder
                    ])
                @endforeach
            </ul>
        @endif
    </section>
@endsection
