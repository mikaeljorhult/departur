@extends('layouts.admin')

@section('content')
    <header>
        <h1>Calendars<span> | {{ $calendar->name }}</span></h1>
    </header>

    <section>
        {!! Form::model($calendar, ['route' => ['calendars.update', $calendar->id], 'method' => 'PUT', 'files' => true]) !!}
            @include('calendars._form', ['submitButtonText' => 'Update'])
        {!! Form::close() !!}
    </section>
@endsection
