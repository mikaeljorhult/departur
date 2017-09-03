<?php

namespace Tests\Feature;

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
}
