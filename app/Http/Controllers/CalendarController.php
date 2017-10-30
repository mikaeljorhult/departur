<?php

namespace Departur\Http\Controllers;

use Departur\Calendar;
use Departur\Http\Requests\CalendarDestroyRequest;
use Departur\Http\Requests\CalendarStoreRequest;
use Departur\Http\Requests\CalendarUpdateRequest;
use Departur\Jobs\ImportCalendar;
use Departur\Schedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Constructor.
     *
     * Limits access to authenticated users.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $calendars = Calendar::orderBy('name')->get();

        return view('calendars.index')
            ->with('calendars', $calendars);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schedules = Schedule::orderBy('name')->get();

        return view('calendars.create')
            ->with('schedules', $schedules);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Departur\Http\Requests\CalendarStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CalendarStoreRequest $request)
    {
        $calendar = Calendar::create($request->all());

        $this->syncSchedules($request, $calendar);

        dispatch(new ImportCalendar($calendar));

        return redirect('/calendars');
    }

    /**
     * Display the specified resource.
     *
     * @param \Departur\Calendar $calendar
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Calendar $calendar)
    {
        return view('calendars.show')
            ->with('calendar', $calendar);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Departur\Calendar $calendar
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendar $calendar)
    {
        $schedules = Schedule::orderBy('name')->get();

        return view('calendars.edit')
            ->with('calendar', $calendar)
            ->with('schedules', $schedules);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Departur\Http\Requests\CalendarUpdateRequest $request
     * @param \Departur\Calendar                            $calendar
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CalendarUpdateRequest $request, Calendar $calendar)
    {
        $calendar->update($request->all());

        $this->syncSchedules($request, $calendar);

        dispatch(new ImportCalendar($calendar));

        return redirect('/calendars');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Departur\Calendar                             $calendar
     * @param \Departur\Http\Requests\CalendarDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar, CalendarDestroyRequest $request)
    {
        $calendar->delete();

        return redirect('/calendars');
    }

    /**
     * Get schedules from request and sync relationship.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Departur\Calendar                      $calendar
     */
    private function syncSchedules(FormRequest $request, Calendar $calendar)
    {
        // Remove all schedule relationships if no schedules are provided.
        if (!$request->has('schedules')) {
            $calendar->schedules()->sync([]);

            return;
        }

        // Get all supplied schedules.
        $schedules = Schedule::with('calendars')
                             ->whereIn('id', $request->input('schedules'))
                             ->get();

        // Go through and reorder all calendars attached to all supplied schedules.
        $schedules->each(function ($schedule) use ($calendar) {
            $calendars = $schedule->calendars
                ->push($calendar)
                ->pluck('id')
                ->unique()
                ->mapWithKeys(function ($item, $key) {
                    return [
                        $item => ['sort_order' => $key],
                    ];
                });

            $schedule->calendars()->sync($calendars);
        });
    }
}
