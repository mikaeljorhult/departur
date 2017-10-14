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
                    <i class="icon" data-icon="columns"></i>
                    Schedules
                </a>
            </div>

            <div class="column">
                <a href="{{ route('calendars.index') }}">
                    <i class="icon" data-icon="calendar"></i>
                    Calendars
                </a>
            </div>

            <div class="column">
                <a href="{{ route('users.index') }}">
                    <i class="icon" data-icon="user"></i>
                    Users
                </a>
            </div>
        </div>
    </section>
@endsection
