@extends('layouts.admin')

@section('content')
    <header>
        <h1>Users</h1>
    </header>

    <section>
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
                        <th scope="row"></th>
                        <td><a href="{{ route('users.edit', $user) }}">{{ $user->email }}</a></td>
                        <td>{{ $user->name }}</td>
                        <td class="table-actions">
                            @unless(auth()->user()->is($user))
                                {!! Form::model($user, ['route' => ['users.destroy', $user->id], 'method' => 'DELETE' ]) !!}
                                    {!! Form::submit('Delete', ['class' => 'button button-outline']) !!}
                                {!! Form::close() !!}
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No users was found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <footer>
        <a href="{{ route('users.create') }}" class="button button-outline">New User</a>
    </footer>
@endsection
