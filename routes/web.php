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

Route::get('/', 'HomeController@index')->name('home');
Route::get('admin', 'AdminController@index')->name('admin');

Auth::routes();

Route::resource('schedules', 'ScheduleController');
Route::get('/s/{slug}', 'ScheduleController@display');
Route::resource('calendars', 'CalendarController');
Route::resource('users', 'UserController');
