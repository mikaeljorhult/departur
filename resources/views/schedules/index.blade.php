@extends('layouts.app')

@section('content')
    <main class="container-fluid">
        <div class="table-responsive">
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
                            <td>{{ $schedule->name }}</td>
                            <td>{{ $schedule->slug }}</td>
                            <td>
                                <a href="{{ route('schedules.edit', $schedule) }}">E</a>
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
        </div>
    </main>
@endsection
