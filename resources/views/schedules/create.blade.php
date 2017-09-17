@extends('layouts.app')

@section('content')
    <main class="container-fluid">
        <div class="page-header">
            <h1>Create Schedule</h1>
        </div>

        {!! Form::open(['route' => 'schedules.store']) !!}
            @include('schedules._form', ['submitButtonText' => 'Create Schedule'])
        {!! Form::close() !!}
    </main>
@endsection
