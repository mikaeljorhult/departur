@extends('layouts.admin')

@section('content')
    <header>
        <h1>Calendars<span> | New</span></h1>
    </header>

    <section>
        {!! Form::open(['route' => 'calendars.store']) !!}
            @include('calendars._form', ['submitButtonText' => 'Create'])
        {!! Form::close() !!}
    </section>
@endsection
