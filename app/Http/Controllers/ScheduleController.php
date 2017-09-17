<?php

namespace Departur\Http\Controllers;

use Departur\Http\Requests\ScheduleDestroyRequest;
use Departur\Http\Requests\ScheduleStoreRequest;
use Departur\Http\Requests\ScheduleUpdateRequest;
use Departur\Schedule;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::all();

        return view('schedules.index')
            ->with('schedules', $schedules);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('schedules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Departur\Http\Requests\ScheduleStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ScheduleStoreRequest $request)
    {
        Schedule::create($request->all());

        return redirect('/schedules');
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
        return view('schedules.edit')
            ->with('schedule', $schedule);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Departur\Http\Requests\ScheduleUpdateRequest $request
     * @param \Departur\Schedule $schedule
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ScheduleUpdateRequest $request, Schedule $schedule)
    {
        $schedule->update($request->all());

        return redirect('/schedules');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Departur\Schedule $schedule
     * @param \Departur\Http\Requests\ScheduleDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule, ScheduleDestroyRequest $request)
    {
        $schedule->delete();
        return redirect('/schedules');
    }
}
