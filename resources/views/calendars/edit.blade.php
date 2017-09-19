@extends('layouts.app')

@section('content')
    <main class="container-fluid">
        <div class="page-header">
            <h1>Edit {{ $calendar->name }}</h1>
        </div>

        {!! Form::model($calendar, ['route' => ['calendars.update', $calendar->id], 'method' => 'PUT' ]) !!}
            @include('calendars._form', ['submitButtonText' => 'Update Calendar'])
        {!! Form::close() !!}
    </main>
@endsection
