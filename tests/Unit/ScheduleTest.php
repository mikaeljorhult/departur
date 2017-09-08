<?php

namespace Tests\Unit;

use Departur\Calendar;
use Departur\Schedule;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A schedule is active if it has at least one active calendar.
     *
     * @return void
     */
    public function testScheduleIsActiveIfItHasActiveCalendars()
    {
        $activeScheduleA  = factory(Schedule::class)->create();
        $activeScheduleB  = factory(Schedule::class)->create();
        $inactiveSchedule = factory(Schedule::class)->create();

        $activeScheduleA->calendars()->saveMany([
            factory(Calendar::class)->states('active')->create()
        ]);

        $activeScheduleB->calendars()->saveMany([
            factory(Calendar::class)->states('active')->create(),
            factory(Calendar::class)->states('inactive')->create()
        ]);

        $inactiveSchedule->calendars()->saveMany([
            factory(Calendar::class)->states('inactive')->create()
        ]);

        $schedules = Schedule::active()->get();

        $this->assertTrue($schedules->contains($activeScheduleA));
        $this->assertTrue($schedules->contains($activeScheduleB));
        $this->assertFalse($schedules->contains($inactiveSchedule));
    }
}
