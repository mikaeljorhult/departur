<section class="row">
    <div class="column">
        <div class="form-group">
            {!! Form::label('name', 'Name') !!}
            {!! Form::text('name', null, ['placeholder' => 'Name']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('start_date', 'Start Date') !!}
            {!! Form::text('start_date', null, ['placeholder' => 'Start Date']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('end_date', 'End Date') !!}
            {!! Form::text('end_date', null, ['placeholder' => 'End Date']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('url', 'URL') !!}
            {!! Form::text('url', null, ['placeholder' => 'URL']) !!}
        </div>
    </div>

    <div class="column">
        {!! Form::label('schedules', 'Schedules') !!}
        <ul class="sortable schedulelist">
            @foreach($schedules as $schedule)
                <li>
                    {!! Form::checkbox('schedules[]', $schedule->id, isset($calendar) && $calendar->schedules->contains('id', $schedule->id)) !!}
                    {{ $schedule->name }}
                </li>
            @endforeach
        </ul>
    </div>
</section>

<section class="row">
    <div class="column">
        <div class="form-group">
            {!! Form::submit($submitButtonText) !!}
        </div>
    </div>
</section>