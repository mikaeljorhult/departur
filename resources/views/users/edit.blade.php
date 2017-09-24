@extends('layouts.admin')

@section('content')
    <header>
        <h1>Users<span> | {{ $user->name }}</span></h1>
    </header>

    <section>
        {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PUT' ]) !!}
            @include('users._form', ['submitButtonText' => 'Update'])
        {!! Form::close() !!}
    </section>
@endsection
