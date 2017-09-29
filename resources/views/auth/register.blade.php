@extends('layouts.app')

@section('content')
    {!! Form::open(['route' => 'register']) !!}
        {{ csrf_field() }}

        <div class="{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', 'Name') !!}
            {!! Form::text('name', null, ['required' => true, 'autofocus' => true]) !!}

            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>

        <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
            {!! Form::label('email', 'E-mail Address') !!}
            {!! Form::email('email', null, ['required' => true]) !!}

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
            {!! Form::label('password_confirmation', 'Password Confirmation') !!}
            {!! Form::password('password_confirmation', ['required' => true]) !!}
        </div>

        <div>
            <input type="submit" value="Register" class="button" />
        </div>
    {!! Form::close() !!}
</div>
@endsection
