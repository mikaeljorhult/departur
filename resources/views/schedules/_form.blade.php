<section class="row">
    <div class="column">
        <div class="form-group">
            {!! Form::label('name', 'Name') !!}
            {!! Form::text('name', null, ['placeholder' => 'Name']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('slug', 'Slug') !!}
            {!! Form::text('slug', null, ['placeholder' => 'Slug']) !!}
        </div>
    </div>

    <div class="column">
        {!! Form::label('calendars', 'Calendars') !!}
        @if(isset($schedule) && $schedule->calendars->count())
            <ul>
                @foreach($schedule->calendars as $calendar)
                    <li>
                        {!! Form::hidden('calendars[]', $calendar->id) !!}
                        {{ $calendar->name }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>

<section class="row">
    <div class="column">
        {!! Form::submit($submitButtonText) !!}
    </div>
</section>