@extends('layouts.app')

@section('content')
    <main class="container-fluid">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($calendars as $calendar)
                        <tr>
                            <th scope="row">&nbsp;</th>
                            <td>{{ $calendar->name }}</td>
                            <td>{{ $calendar->start_date->format('Y-m-d') }}</td>
                            <td>{{ $calendar->end_date->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('calendars.edit', $calendar) }}">E</a>
                                <a href="{{ route('calendars.destroy', $calendar) }}">D</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No calendars was found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
@endsection