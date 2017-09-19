<div class="form-group">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
</div>

<div class="form-group">
    {!! Form::label('start_date', 'Start Date') !!}
    {!! Form::text('start_date', null, ['class' => 'form-control date-field', 'placeholder' => 'Start Date']) !!}
</div>

<div class="form-group">
    {!! Form::label('end_date', 'End Date') !!}
    {!! Form::text('end_date', null, ['class' => 'form-control date-field', 'placeholder' => 'End Date']) !!}
</div>

<div class="form-group">
    {!! Form::label('url', 'URL') !!}
    {!! Form::text('url', null, ['class' => 'form-control', 'placeholder' => 'URL']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
</div>