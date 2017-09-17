@extends('layouts.app')

@section('content')
    <main class="container-fluid">
        <div class="page-header">
            <h1>Edit {{ $schedule->name }}</h1>
        </div>

        {!! Form::model($schedule, ['route' => ['schedules.update', $schedule->id], 'method' => 'PUT' ]) !!}
            @include('schedules._form', ['submitButtonText' => 'Update Schedule'])
        {!! Form::close() !!}
    </main>
@endsection
