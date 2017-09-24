@extends('layouts.admin')

@section('content')
    <header>
        <h1>Schedules<span> | {{ $schedule->name }}</span></h1>
    </header>

    <section>
        {!! Form::model($schedule, ['route' => ['schedules.update', $schedule->id], 'method' => 'PUT' ]) !!}
            @include('schedules._form', ['submitButtonText' => 'Update'])
        {!! Form::close() !!}
    </section>
@endsection
