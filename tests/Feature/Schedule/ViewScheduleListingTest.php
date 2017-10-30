<?php

namespace Tests\Feature;

use Departur\Calendar;
use Departur\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewScheduleListingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can view the schedule listing.
     *
     * @return void
     */
    public function testUserCanViewScheduleListing()
    {
        $activeSchedule = factory(Schedule::class)->create();
        $activeSchedule->calendars()->saveMany([
            factory(Calendar::class)->states('active')->create(),
        ]);

        $inactiveScheduleA = factory(Schedule::class)->create();
        $inactiveScheduleB = factory(Schedule::class)->create();
        $inactiveScheduleB->calendars()->saveMany([
            factory(Calendar::class)->states('inactive')->create(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($activeSchedule->name);
        $response->assertDontSee($inactiveScheduleA->name);
        $response->assertDontSee($inactiveScheduleB->name);
    }
}
