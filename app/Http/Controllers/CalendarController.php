<?php

namespace Departur\Http\Controllers;

use Departur\Calendar;
use Departur\Http\Requests\CalendarStoreRequest;
use Departur\Http\Requests\CalendarUpdateRequest;
use Illuminate\Http\Request;

class CalendarController extends Controller
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
        Calendar::create($request->all());

        return redirect('/calendars');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Departur\Calendar $calendar
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Calendar $calendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Departur\Calendar $calendar
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendar $calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Departur\Http\Requests\CalendarUpdateRequest $request
     * @param  \Departur\Calendar $calendar
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CalendarUpdateRequest $request, Calendar $calendar)
    {
        $calendar->update($request->all());

        return redirect('/calendars');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Departur\Calendar $calendar
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        //
    }
}
