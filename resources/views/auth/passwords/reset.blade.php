@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    {!! Form::open(['route' => 'password.request']) !!}
        {!! Form::hidden('token', $token) !!}

        <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
            {!! Form::label('email', 'E-Mail Address') !!}
            {!! Form::email('email', $email ?? old('email'), ['required' => true, 'autofocus' => true]) !!}

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
            {!! Form::label('password', 'Password') !!}
            {!! Form::password('password', ['required' => true]) !!}

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            {!! Form::label('password_confirmation', 'Confirm Password') !!}
            {!! Form::password('password_confirmation', ['required' => true]) !!}

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>

        <div>
            {!! Form::submit('Reset Password', ['class' => 'button']) !!}
        </div>
    {!! Form::close() !!}
@endsection
