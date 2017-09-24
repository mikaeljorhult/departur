@extends('layouts.admin')

@section('content')
    <header>
        <h1>Schedules<span> | New</span></h1>
    </header>

    <section>
        {!! Form::open(['route' => 'schedules.store']) !!}
            @include('schedules._form', ['submitButtonText' => 'Create'])
        {!! Form::close() !!}
    </section>
@endsection
