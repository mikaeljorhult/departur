<?php

namespace Tests\Feature;

use Departur\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can create a schedule.
     *
     * @return void
     */
    public function testUserCanCreateSchedule()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/schedules', [
            'name' => 'Test Schedule',
            'slug' => 'test-schedule',
        ]);

        $response->assertRedirect('schedules.index');
        $this->assertDatabaseHas('schedules', [
            'name' => 'Test Schedule',
            'slug' => 'test-schedule',
        ]);
    }

    /**
     * A visitor can not create a schedule.
     *
     * @return void
     */
    public function testVisitorCanNotCreateSchedule()
    {
        $response = $this->post('/schedules', [
            'name' => 'Test Schedule',
            'slug' => 'test-schedule',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('schedules', [
            'name' => 'Test Schedule',
            'slug' => 'test-schedule',
        ]);
    }
}
