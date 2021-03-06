@extends('layouts.app')

@section('title', 'Login')

@section('content')
    {!! Form::open(['route' => 'login']) !!}
        <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
            {!! Form::label('email', 'E-mail Address') !!}
            {!! Form::email('email', null, ['required' => true, 'autofocus' => true]) !!}

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

        <div>
            <label>
                {!! Form::checkbox('remember', old('remember') ? 'checked' : '') !!} Remember Me
            </label>
        </div>

        <div>
            {!! Form::submit('Login', ['class' => 'button']) !!}

            <a class="button button-clear" href="{{ route('password.request') }}">
                Forgot Your Password?
            </a>
        </div>
    {!! Form::close() !!}
@endsection
