@extends('layouts.admin')

@section('content')
    <header>
        <h1>Schedules</h1>
    </header>

    <section>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                @forelse($schedules as $schedule)
                    <tr>
                        <th scope="row">&nbsp;</th>
                        <td><a href="{{ route('schedules.edit', $schedule) }}">{{ $schedule->name }}</a></td>
                        <td>{{ $schedule->slug }}</td>
                        <td>
                            <a href="{{ route('schedules.destroy', $schedule) }}">D</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No schedules was found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <footer>
        <a href="{{ route('schedules.create') }}" class="button button-outline">New Schedule</a>
    </footer>
@endsection
