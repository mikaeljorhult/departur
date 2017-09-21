<?php

namespace Tests\Feature;

use Departur\Schedule;
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

        $response->assertRedirect('/schedules');
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

    /**
     * Schedules must have a name.
     *
     * @return void
     */
    public function testScheduleMustHaveAName()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/schedules', [
            'slug' => 'test-schedule',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('schedules', [
            'slug' => 'test-schedule',
        ]);
    }

    /**
     * Schedules must have a slug.
     *
     * @return void
     */
    public function testScheduleMustHaveASlug()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/schedules', [
            'name' => 'Test Schedule',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('schedules', [
            'name' => 'Test Schedule',
        ]);
    }

    /**
     * Schedule slug must be unique.
     *
     * @return void
     */
    public function testSlugMustBeUnique()
    {
        factory(Schedule::class)->create(['slug' => 'test-schedule']);

        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/schedules', [
            'name' => 'Test Schedule',
            'slug' => 'test-schedule',
        ]);

        $response->assertRedirect();
    }

    /**
     * Schedule slug must be less than 100 characters.
     *
     * @return void
     */
    public function testSlugMustBeLessThan100Characters()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/schedules', [
            'name' => 'Test Schedule',
            'slug' => str_random(101),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('schedules', [
            'name' => 'Test Schedule',
        ]);
    }
}
