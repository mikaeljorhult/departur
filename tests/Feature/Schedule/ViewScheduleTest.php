<?php

namespace Tests\Feature;

use Departur\Calendar;
use Departur\Event;
use Departur\Schedule;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can view a schedule.
     *
     * @return void
     */
    public function testUserCanViewSchedule()
    {
        $schedule = factory(Schedule::class)->create();

        $response = $this->get('/schedules/' . $schedule->id);

        $response->assertStatus(200);
        $response->assertSee($schedule->name);
    }

    /**
     * A user can view a schedule via slug.
     *
     * @return void
     */
    public function testUserCanViewScheduleViaSlug()
    {
        $schedule = factory(Schedule::class)->create();

        $response = $this->get('/s/' . $schedule->slug);

        $response->assertStatus(200);
        $response->assertSee($schedule->name);
    }

    /**
     * A user can view events in schedule.
     *
     * @return void
     */
    public function testUserCanViewEventsInSchedule()
    {
        $schedule = factory(Schedule::class)->create();
        $calendar = factory(Calendar::class)->create();
        $event    = factory(Event::class)->create();

        $schedule->calendars()->save($calendar);
        $calendar->events()->save($event);

        $response = $this->get('/s/' . $schedule->slug);

        $response->assertStatus(200);
        $response->assertSee($schedule->name);
        $response->assertSee($event->title);
        $response->assertSee($event->location);
        $response->assertSee($event->description);
    }
}
