@extends('layouts.admin')

@section('title', 'Administration')

@section('content')
    <header>
        <h1>Administration</h1>
    </header>

    <section>
        <div class="row entrances">
            <div class="column">
                <a href="{{ route('schedules.index') }}">
                    @svg('browser-window')
                    Schedules
                </a>
            </div>

            <div class="column">
                <a href="{{ route('calendars.index') }}">
                    @svg('calendar')
                    Calendars
                </a>
            </div>

            <div class="column">
                <a href="{{ route('users.index') }}">
                    @svg('user')
                    Users
                </a>
            </div>
        </div>
    </section>
@endsection
