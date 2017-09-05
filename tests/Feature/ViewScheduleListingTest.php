<?php

namespace Tests\Feature;

use Departur\Schedule;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $schedule = factory(Schedule::class)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($schedule->name);
    }
}
