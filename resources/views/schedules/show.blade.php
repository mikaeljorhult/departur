@extends('layouts.app')

@section('title', $schedule->name)

@section('content')
    <h1>{{ $schedule->name }}</h1>

    @if($schedule->calendars->count() > 0)
        <ul>
            @foreach($schedule->calendars as $calendar)
                <li>{{ $calendar->name }}</li>
            @endforeach
        </ul>
    @endif

    <section class="schedule">
        @if($schedule->events->count() > 0)
            <ul class="list-days">
                @each('schedules._day', $schedule->events->groupBy(function ($item, $key) {
                    return $item->start_time->format('Y-m-d');
                }), 'events')
            </ul>
        @endif
    </section>
@endsection
