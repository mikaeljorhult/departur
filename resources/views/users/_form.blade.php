<div class="form-group">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['placeholder' => 'Name']) !!}
</div>

<div class="form-group">
    {!! Form::label('email', 'E-mail') !!}
    {!! Form::email('email', null, ['placeholder' => 'E-mail']) !!}
</div>

<div class="form-group">
    {!! Form::label('password', 'Password') !!}
    {!! Form::password('password', ['placeholder' => 'Password']) !!}
</div>

<div class="form-group">
    {!! Form::label('password_confirmation', 'Password Confirmation') !!}
    {!! Form::password('password_confirmation', ['placeholder' => 'Password Confirmation']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText) !!}
</div>