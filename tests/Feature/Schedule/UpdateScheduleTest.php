<?php

namespace Tests\Feature;

use Departur\Schedule;
use Departur\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can update a schedule.
     *
     * @return void
     */
    public function testUserCanUpdateSchedule()
    {
        $schedule = factory(Schedule::class)->create([
            'name' => 'Test Schedule',
        ]);

        $this->actingAs(factory(User::class)->create());

        $response = $this->put('/schedules/' . $schedule->id, [
            'name' => 'Updated Schedule',
            'slug' => $schedule->slug,
        ]);

        $response->assertRedirect('/schedules');
        $this->assertDatabaseHas('schedules', [
            'name' => 'Updated Schedule',
        ]);
    }

    /**
     * A visitor can not update a schedule.
     *
     * @return void
     */
    public function testVisitorCanNotUpdateSchedule()
    {
        $schedule = factory(Schedule::class)->create([
            'name' => 'Test Schedule',
        ]);

        $response = $this->put('/schedules/' . $schedule->id, [
            'name' => 'Updated Schedule',
            'slug' => $schedule->slug,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('schedules', [
            'name' => 'Updated Schedule',
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
        $schedule = factory(Schedule::class)->create();

        $response = $this->put('/schedules/' . $schedule->id, [
            'name' => '',
            'slug' => $schedule->slug,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('schedules', [
            'name' => $schedule->name,
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
        $schedule = factory(Schedule::class)->create();

        $response = $this->put('/schedules/' . $schedule->id, [
            'name' => $schedule->name,
            'slug' => '',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('schedules', [
            'slug' => $schedule->slug,
        ]);
    }

    /**
     * Schedule slug must be unique.
     *
     * @return void
     */
    public function testSlugMustBeUnique()
    {
        $this->actingAs(factory(User::class)->create());

        factory(Schedule::class)->create(['slug' => 'test-schedule']);
        $schedule = factory(Schedule::class)->create();

        $response = $this->put('/schedules/' . $schedule->id, [
            'name' => 'Updated Schedule',
            'slug' => 'test-schedule',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('schedules', [
            'name' => 'Updated Schedule',
        ]);
    }

    /**
     * Schedule slug must be less than 100 characters.
     *
     * @return void
     */
    public function testSlugMustBeLessThan100Characters()
    {
        $this->actingAs(factory(User::class)->create());
        $schedule = factory(Schedule::class)->create();

        $response = $this->put('/schedules/' . $schedule->id, [
            'name' => 'Updated Schedule',
            'slug' => str_random(101),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('schedules', [
            'name' => 'Updated Schedule',
        ]);
    }

    /**
     * Schedule slug is converted to lowercase.
     *
     * @return void
     */
    public function testSlugIsMadeLowercase()
    {
        $schedule = factory(Schedule::class)->create([
            'slug' => 'schedule',
        ]);

        $this->actingAs(factory(User::class)->create());

        $response = $this->put('/schedules/' . $schedule->id, [
            'name' => 'Updated Schedule',
            'slug' => 'TEST-schedule',
        ]);

        $response->assertRedirect('/schedules');
        $this->assertDatabaseHas('schedules', [
            'slug' => 'test-schedule',
        ]);
    }
}
