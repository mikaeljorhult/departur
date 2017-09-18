<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Departur\Http\Controllers\ScheduleController;

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('schedules', 'ScheduleController');
Route::get('/s/{slug}', 'ScheduleController@display');
Route::resource('calendars', 'CalendarController');
