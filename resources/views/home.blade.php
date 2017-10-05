@extends('layouts.app')

@section('content')
    <ul>
        @foreach($schedules as $schedule)
            <li>
                <a href="{{ route('schedules.show', $schedule) }}">{{ $schedule->name }}</a>
            </li>
        @endforeach
    </ul>
@endsection
