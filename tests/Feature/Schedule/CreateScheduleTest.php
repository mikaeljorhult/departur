<?php

namespace Tests\Feature;

use Departur\Calendar;
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

        $response->assertRedirect('/login');
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

    /**
     * Schedule slug is converted to lowercase.
     *
     * @return void
     */
    public function testSlugIsMadeLowercase()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/schedules', [
            'name' => 'Test Schedule',
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
        $calendar = factory(Calendar::class)->create();

        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/schedules', [
            'name'      => 'Test Schedule',
            'slug'      => 'test-schedule',
            'calendars' => [$calendar->id],
        ]);

        $response->assertRedirect('/schedules');
        $this->assertDatabaseHas('calendar_schedule', [
            'calendar_id' => $calendar->id,
        ]);
    }

    /**
     * Relationships with calendars are ordered.
     *
     * @return void
     */
    public function testCalendarRelationshipsAreOrdered()
    {
        $calendars = factory(Calendar::class, 2)->create();

        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/schedules', [
            'name'      => 'Test Schedule',
            'slug'      => 'test-schedule',
            'calendars' => [$calendars[1]->id, $calendars[0]->id],
        ]);

        $response->assertRedirect('/schedules');
        $this->assertDatabaseHas('calendar_schedule', [
            'calendar_id' => $calendars[1]->id,
            'sort_order'  => 0,
        ]);
        $this->assertDatabaseHas('calendar_schedule', [
            'calendar_id' => $calendars[0]->id,
            'sort_order'  => 1,
        ]);
    }

    /**
     * Relationships are not created with missing calendars.
     *
     * @return void
     */
    public function testCalendarMustExist()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/schedules', [
            'name'      => 'Test Schedule',
            'slug'      => 'test-schedule',
            'calendars' => [100],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendar_schedule', [
            'calendar_id' => 100,
        ]);
    }
}
