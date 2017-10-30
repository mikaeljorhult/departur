<?php

namespace Departur\Http\Controllers;

use Departur\Schedule;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::active()->orderBy('name')->get();

        return view('home')->with('schedules', $schedules);
    }
}
