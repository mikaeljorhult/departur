<?php

namespace Departur\Http\Controllers;

use Departur\Schedule;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::all();

        return view('home')->with('schedules', $schedules);
    }
}
