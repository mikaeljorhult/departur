@extends('layouts.admin')

@section('content')
    <header>
        <h1>Administration</h1>
    </header>

    <section>
        <div class="row">
            <div class="column">
                <a href="{{ route('schedules.index') }}">Schedules</a>
            </div>

            <div class="column">
                <a href="{{ route('calendars.index') }}">Calendars</a>
            </div>

            <div class="column">
                <a href="{{ route('users.index') }}">Users</a>
            </div>
        </div>
    </section>
@endsection
