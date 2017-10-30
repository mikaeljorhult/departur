<?php

namespace Tests\Feature;

use Departur\Calendar;
use Departur\Schedule;
use Departur\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyCalendarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can destroy a calendar.
     *
     * @return void
     */
    public function testUserCanDestroyCalendar()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->delete('/calendars/'.$calendar->id);

        $response->assertRedirect('/calendars');
        $this->assertDatabaseMissing('calendars', [
            'name' => $calendar->name,
        ]);
    }

    /**
     * A visitor can not destroy a calendar.
     *
     * @return void
     */
    public function testVisitorCanNotDestroyCalendar()
    {
        $calendar = factory(Calendar::class)->create();

        $response = $this->delete('/calendars/'.$calendar->id);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('calendars', [
            'name' => $calendar->name,
        ]);
    }

    /**
     * Relationships to schedules are destroyed with calendar.
     *
     * @return void
     */
    public function testRelationshipsAreDestroyedWithCalendar()
    {
        $this->actingAs(factory(User::class)->create());

        $calendar = factory(Calendar::class)->create();
        $schedule = factory(Schedule::class)->create();
        $calendar->schedules()->attach($schedule);

        $response = $this->delete('/calendars/'.$calendar->id);

        $response->assertRedirect('/calendars');
        $this->assertDatabaseMissing('calendar_schedule', [
            'schedule_id' => $schedule->id,
        ]);
    }
}
