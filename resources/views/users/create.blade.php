@extends('layouts.app')

@section('content')
    <main class="container-fluid">
        <div class="page-header">
            <h1>Create User</h1>
        </div>

        {!! Form::open(['route' => 'users.store']) !!}
            @include('users._form', ['submitButtonText' => 'Create User'])
        {!! Form::close() !!}
    </main>
@endsection
