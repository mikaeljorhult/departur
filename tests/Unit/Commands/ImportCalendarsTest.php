<?php

namespace Tests\Unit\Commands;

use Departur\Calendar;
use Departur\Jobs\ImportCalendar;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportCalendarsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Fake Queue facade for all test to avoid running any dispatched jobs.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Queue::fake();
    }

    /**
     * A job are dispatched for an active calendar.
     *
     * @return void
     */
    public function testJobsAreQueuedForActiveCalendars()
    {
        $calendar = factory(Calendar::class)->states('active')->create();

        Artisan::call('departur:import');

        Queue::assertPushed(ImportCalendar::class, function ($job) use ($calendar) {
            return $job->calendar->id === $calendar->id;
        });
    }

    /**
     * A job are not dispatched for an inactive calendar.
     *
     * @return void
     */
    public function testJobsAreNotQueuedForInactiveCalendars()
    {
        factory(Calendar::class)->states('inactive')->create();

        Artisan::call('departur:import');

        Queue::assertNotPushed(ImportCalendar::class);
    }

    /**
     * Commands are only queued for active calendars.
     *
     * @return void
     */
    public function testJobsAreQueuedForMultipleActiveCalendars()
    {
        factory(Calendar::class, 2)->states('active')->create();
        factory(Calendar::class, 4)->states('inactive')->create();

        Artisan::call('departur:import');

        Queue::assertPushed(ImportCalendar::class, 2);
    }
}
