<div class="form-group">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['placeholder' => 'Name']) !!}
</div>

<div class="form-group">
    {!! Form::label('slug', 'Slug') !!}
    {!! Form::text('slug', null, ['placeholder' => 'Slug']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText) !!}
</div>