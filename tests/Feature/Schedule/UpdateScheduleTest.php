<?php

namespace Tests\Feature;

use Departur\Calendar;
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

        $response->assertRedirect('/login');
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

    /**
     * Relationships with supplied calendars are created.
     *
     * @return void
     */
    public function testCalendarRelationshipsAreCreated()
    {
        $schedule = factory(Schedule::class)->create();
        $calendar = factory(Calendar::class)->create();

        $this->actingAs(factory(User::class)->create());

        $response = $this->put('/schedules/' . $schedule->id, [
            'name'      => $schedule->name,
            'slug'      => $schedule->slug,
            'calendars' => [$calendar->id],
        ]);

        $response->assertRedirect('/schedules');
        $this->assertDatabaseHas('calendar_schedule', [
            'calendar_id' => $calendar->id,
            'schedule_id' => $schedule->id,
        ]);
    }

    /**
     * Relationships are not created with missing calendars.
     *
     * @return void
     */
    public function testCalendarMustExist()
    {
        $schedule = factory(Schedule::class)->create();

        $this->actingAs(factory(User::class)->create());

        $response = $this->put('/schedules/' . $schedule->id, [
            'name'      => $schedule->name,
            'slug'      => $schedule->slug,
            'calendars' => [100],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendar_schedule', [
            'schedule_id' => $schedule->id,
        ]);
    }

    /**
     * Relationships with missing calendars are destroyed.
     *
     * @return void
     */
    public function testCalendarRelationshipsAreRemoved()
    {
        $schedule = factory(Schedule::class)->create();
        $calendar = factory(Calendar::class)->create();
        $schedule->calendars()->attach($calendar);

        $this->actingAs(factory(User::class)->create());

        $response = $this->put('/schedules/' . $schedule->id, [
            'name' => $schedule->name,
            'slug' => $schedule->slug,
        ]);

        $response->assertRedirect('/schedules');
        $this->assertDatabaseMissing('calendar_schedule', [
            'calendar_id' => $calendar->id,
            'schedule_id' => $schedule->id,
        ]);
    }

    /**
     * Relationships with calendars are ordered.
     *
     * @return void
     */
    public function testCalendarRelationshipsAreOrdered()
    {
        $schedule  = factory(Schedule::class)->create();
        $calendars = factory(Calendar::class, 2)->create();
        $schedule->calendars()->attach($calendars);

        $this->actingAs(factory(User::class)->create());

        $response = $this->put('/schedules/' . $schedule->id, [
            'name'      => $schedule->name,
            'slug'      => $schedule->slug,
            'calendars' => $calendars->pluck('id'),
        ]);

        $response->assertRedirect('/schedules');
        $this->assertDatabaseHas('calendar_schedule', [
            'calendar_id' => $calendars[0]->id,
            'schedule_id' => $schedule->id,
            'sort_order'  => 0,
        ]);
        $this->assertDatabaseHas('calendar_schedule', [
            'calendar_id' => $calendars[1]->id,
            'schedule_id' => $schedule->id,
            'sort_order'  => 1,
        ]);
    }
}
