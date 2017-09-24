@extends('layouts.admin')

@section('content')
    <header>
        <h1>Users<span> | New</span></h1>
    </header>

    <section>
        {!! Form::open(['route' => 'users.store']) !!}
            @include('users._form', ['submitButtonText' => 'Create'])
        {!! Form::close() !!}
    </section>
@endsection
