@extends('layouts.admin')

@section('content')
    <header>
        <h1>Calendars</h1>
    </header>

    <section>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>URL</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                @forelse($calendars as $calendar)
                    <tr>
                        <th scope="row"></th>
                        <td><a href="{{ route('calendars.edit', $calendar) }}">{{ $calendar->name }}</a></td>
                        <td>{{ $calendar->start_date->format('Y-m-d') }}</td>
                        <td>{{ $calendar->end_date->format('Y-m-d') }}</td>
                        <td><i class="icon calendar-url" data-icon="calendar" title="{{ $calendar->url }}"></i></td>
                        <td class="table-actions">
                            {!! Form::model($calendar, ['route' => ['calendars.destroy', $calendar->id], 'method' => 'DELETE' ]) !!}
                                {!! Form::submit('Delete', ['class' => 'button button-outline resource-delete']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No calendars was found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <footer>
        <a href="{{ route('calendars.create') }}" class="button button-outline">New Calendar</a>
    </footer>
@endsection
