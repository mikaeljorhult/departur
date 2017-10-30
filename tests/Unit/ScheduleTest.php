<?php

namespace Tests\Unit;

use Departur\Calendar;
use Departur\Event;
use Departur\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $activeScheduleA = factory(Schedule::class)->create();
        $activeScheduleB = factory(Schedule::class)->create();
        $inactiveSchedule = factory(Schedule::class)->create();

        $activeScheduleA->calendars()->saveMany([
            factory(Calendar::class)->states('active')->create(),
        ]);

        $activeScheduleB->calendars()->saveMany([
            factory(Calendar::class)->states('active')->create(),
            factory(Calendar::class)->states('inactive')->create(),
        ]);

        $inactiveSchedule->calendars()->saveMany([
            factory(Calendar::class)->states('inactive')->create(),
        ]);

        $schedules = Schedule::active()->get();

        $this->assertTrue($schedules->contains($activeScheduleA));
        $this->assertTrue($schedules->contains($activeScheduleB));
        $this->assertFalse($schedules->contains($inactiveSchedule));
    }

    /**
     * A schedule is active if it has at least one active calendar.
     *
     * @return void
     */
    public function testEventsAreCollectedFromAllRelatedCalendars()
    {
        $schedule = factory(Schedule::class)->create();
        $calendarA = factory(Calendar::class)->states('active')->create();
        $calendarB = factory(Calendar::class)->states('active')->create();
        $calendarC = factory(Calendar::class)->states('active')->create();

        $calendarA->events()->saveMany(factory(Event::class, 5)->create());
        $calendarB->events()->saveMany(factory(Event::class, 5)->create());
        $calendarC->events()->saveMany(factory(Event::class, 5)->create());

        $schedule->calendars()->saveMany([
            $calendarA,
            $calendarB,
        ]);

        $events = $schedule->events;

        $this->assertCount(10, $events);
        $this->assertCount(5, $events->diff($calendarA->events));
        $this->assertCount(5, $events->diff($calendarB->events));
        $this->assertCount(10, $events->diff($calendarC->events));
    }
}
