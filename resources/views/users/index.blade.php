@extends('layouts.app')

@section('content')
    <main class="container-fluid">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>E-mail</th>
                        <th>Name</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <th scope="row">&nbsp;</th>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}">E</a>
                                <a href="{{ route('users.destroy', $user) }}">D</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No users was found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
@endsection
