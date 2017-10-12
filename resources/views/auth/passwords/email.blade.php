@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    @if (session('status'))
        <blockquote class="alert alert-success">
            <p><em>{{ session('status') }}</em></p>
        </blockquote>
    @endif

    {!! Form::open(['route' => 'password.email']) !!}
        <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
            {!! Form::label('email', 'E-Mail Address') !!}
            {!! Form::email('email', old('email'), ['required' => true]) !!}

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div>
            {!! Form::submit('Send Password Reset Link', ['class' => 'button']) !!}
        </div>
    {!! Form::close() !!}
@endsection
