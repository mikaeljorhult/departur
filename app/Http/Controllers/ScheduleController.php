<?php

namespace Departur\Http\Controllers;

use Departur\Http\Requests\CalendarStoreRequest;
use Departur\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        Schedule::create($request->all());

        return redirect('schedules.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Departur\Schedule $schedule
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        $schedule->load(['calendars', 'calendars.events']);

        return view('schedules.show')
            ->with('schedule', $schedule);
    }

    /**
     * Display the specified resource via slug.
     *
     * @param  string $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function display($slug)
    {
        $schedule = Schedule::where('slug', $slug)->firstOrFail();

        return $this->show($schedule);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Departur\Schedule $schedule
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Departur\Schedule $schedule
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Departur\Schedule $schedule
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
