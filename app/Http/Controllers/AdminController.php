<?php

namespace Departur\Http\Controllers;

class AdminController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin');
    }
}
