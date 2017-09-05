@extends('layouts.app')

@section('content')
    <div class="container">
        <ul>
            @foreach($schedules as $schedule)
                <li>
                    <a href="{{ route('schedules.show', $schedule) }}">{{ $schedule->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
