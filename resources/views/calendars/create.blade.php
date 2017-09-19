@extends('layouts.app')

@section('content')
    <main class="container-fluid">
        <div class="page-header">
            <h1>Create Calendar</h1>
        </div>

        {!! Form::open(['route' => 'calendars.store']) !!}
            @include('calendars._form', ['submitButtonText' => 'Create Calendar'])
        {!! Form::close() !!}
    </main>
@endsection
