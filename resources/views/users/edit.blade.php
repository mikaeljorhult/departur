@extends('layouts.app')

@section('content')
    <main class="container-fluid">
        <div class="page-header">
            <h1>Edit {{ $user->name }}</h1>
        </div>

        {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PUT' ]) !!}
            @include('users._form', ['submitButtonText' => 'Update User'])
        {!! Form::close() !!}
    </main>
@endsection
