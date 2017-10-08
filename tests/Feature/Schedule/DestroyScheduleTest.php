<?php

namespace Tests\Feature;

use Departur\Calendar;
use Departur\Schedule;
use Departur\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can destroy a schedule.
     *
     * @return void
     */
    public function testUserCanDestroySchedule()
    {
        $this->actingAs(factory(User::class)->create());
        $schedule = factory(Schedule::class)->create();

        $response = $this->delete('/schedules/' . $schedule->id);

        $response->assertRedirect('/schedules');
        $this->assertDatabaseMissing('schedules', [
            'name' => $schedule->name
        ]);
    }

    /**
     * A visitor can not destroy a schedule.
     *
     * @return void
     */
    public function testVisitorCanNotDestroySchedule()
    {
        $schedule = factory(Schedule::class)->create();

        $response = $this->delete('/schedules/' . $schedule->id);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('schedules', [
            'name' => $schedule->name
        ]);
    }

    /**
     * Relationships to calendars are destroyed with schedule.
     *
     * @return void
     */
    public function testRelationshipsAreDestroyedWithSchedule()
    {
        $this->actingAs(factory(User::class)->create());

        $schedule = factory(Schedule::class)->create();
        $calendar = factory(Calendar::class)->create();
        $schedule->calendars()->attach($calendar);

        $response = $this->delete('/schedules/' . $schedule->id);

        $response->assertRedirect('/schedules');
        $this->assertDatabaseMissing('calendar_schedule', [
            'calendar_id' => $calendar->id,
        ]);
    }
}
