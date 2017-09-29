@extends('layouts.app')

@section('content')
    {!! Form::open(['route' => 'login']) !!}
        {{ csrf_field() }}

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
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>
        </div>

        <div>
            <input type="submit" value="Login" class="button" />

            <a class="button button-clear" href="{{ route('password.request') }}">
                Forgot Your Password?
            </a>
        </div>
    {!! Form::close() !!}
@endsection
