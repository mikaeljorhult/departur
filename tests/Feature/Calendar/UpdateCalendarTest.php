<?php

namespace Tests\Feature;

use Departur\Calendar;
use Departur\Schedule;
use Departur\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCalendarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can update a calendar.
     *
     * @return void
     */
    public function testUserCanUpdateCalendar()
    {
        $this->actingAs(factory(User::class)->create());

        $calendar = factory(Calendar::class)->create([
            'name' => 'Test Calendar',
        ]);

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => 'Updated Calendar',
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect('/calendars');
        $this->assertDatabaseHas('calendars', [
            'name' => 'Updated Calendar',
        ]);
    }

    /**
     * A visitor can not update a calendar.
     *
     * @return void
     */
    public function testVisitorCanNotUpdateCalendar()
    {
        $calendar = factory(Calendar::class)->create([
            'name' => 'Test Calendar',
        ]);

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => 'Updated Calendar',
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Updated Calendar',
        ]);
    }

    /**
     * Calendar must have a name.
     *
     * @return void
     */
    public function testCalendarMustHaveAName()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => '',
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('calendars', [
            'name' => $calendar->name,
        ]);
    }

    /**
     * Calendar must have a URL.
     *
     * @return void
     */
    public function testCalendarMustHaveAURL()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => '',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('calendars', [
            'url' => $calendar->url,
        ]);
    }

    /**
     * Calendar URL must be valid.
     *
     * @return void
     */
    public function testURLMustBeValid()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => 'not-a-valid-url',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('calendars', [
            'url' => $calendar->url,
        ]);
    }

    /**
     * Calendar must have a URL.
     *
     * @return void
     */
    public function testCalendarMustHaveAType()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('calendars', [
            'url' => $calendar->url,
        ]);
    }

    /**
     * Calendar URL must be valid.
     *
     * @return void
     */
    public function testTypeMustBeValid()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'type'       => 'not-valid-type',
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('calendars', [
            'url' => $calendar->url,
        ]);
    }

    /**
     * Calendar must have a start date.
     *
     * @return void
     */
    public function testCalendarMustHaveAStartDate()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => '',
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('calendars', [
            'start_date' => $calendar->start_date,
        ]);
    }

    /**
     * Calendar start date must be a valid date.
     *
     * @return void
     */
    public function testStartDateMustBeValidDate()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => 'not-a-valid-date',
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('calendars', [
            'start_date' => $calendar->start_date,
        ]);
    }

    /**
     * Calendar must have an end date.
     *
     * @return void
     */
    public function testCalendarMustHaveAnEndDate()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => $calendar->start_date,
            'end_date'   => '',
            'type'       => 'ical',
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('calendars', [
            'end_date' => $calendar->end_date,
        ]);
    }

    /**
     * Calendar end date must be a valid date.
     *
     * @return void
     */
    public function testEndDateMustBeValidDate()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => $calendar->start_date,
            'end_date'   => 'not-a-valid-date',
            'type'       => 'ical',
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('calendars', [
            'end_date' => $calendar->end_date,
        ]);
    }

    /**
     * Calendar end date must be after start date.
     *
     * @return void
     */
    public function testEndDateMustBeAfterStartDate()
    {
        $this->actingAs(factory(User::class)->create());
        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => '2017-12-01',
            'end_date'   => '2017-01-01',
            'type'       => 'ical',
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('calendars', [
            'end_date' => $calendar->end_date,
        ]);
    }

    /**
     * Relationships with supplied schedules are created.
     *
     * @return void
     */
    public function testScheduleRelationshipsAreCreated()
    {
        $this->actingAs(factory(User::class)->create());

        $calendar = factory(Calendar::class)->create();
        $schedule = factory(Schedule::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => $calendar->url,
            'schedules'  => [$schedule->id]
        ]);

        $response->assertRedirect('/calendars');
        $this->assertDatabaseHas('calendar_schedule', [
            'calendar_id' => $calendar->id,
            'schedule_id' => $schedule->id,
        ]);
    }

    /**
     * Relationships are not created with missing schedules.
     *
     * @return void
     */
    public function testScheduleMustExist()
    {
        $this->actingAs(factory(User::class)->create());

        $calendar = factory(Calendar::class)->create();

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => $calendar->url,
            'schedules'  => [100]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendar_schedule', [
            'calendar_id' => $calendar->id,
        ]);
    }

    /**
     * Relationships with missing schedules are destroyed.
     *
     * @return void
     */
    public function testScheduleRelationshipsAreRemoved()
    {
        $calendar = factory(Calendar::class)->create();
        $schedule = factory(Schedule::class)->create();
        $calendar->schedules()->attach($schedule);

        $this->actingAs(factory(User::class)->create());

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => $calendar->url,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendar_schedule', [
            'calendar_id' => $calendar->id,
            'schedule_id' => $schedule->id,
        ]);
    }

    /**
     * Relationships with schedules are ordered.
     *
     * @return void
     */
    public function testScheduleRelationshipsAreOrdered()
    {
        $this->actingAs(factory(User::class)->create());

        $calendar  = factory(Calendar::class)->create();
        $schedule1 = factory(Schedule::class)->create();
        $schedule2 = factory(Schedule::class)->create();

        $schedule1->calendars()->attach(factory(Calendar::class)->create());

        $response = $this->put('/calendars/' . $calendar->id, [
            'name'       => $calendar->name,
            'start_date' => $calendar->start_date,
            'end_date'   => $calendar->end_date,
            'type'       => 'ical',
            'url'        => $calendar->url,
            'schedules'  => [$schedule1->id, $schedule2->id]
        ]);

        $response->assertRedirect('/calendars');
        $this->assertDatabaseHas('calendar_schedule', [
            'calendar_id' => $calendar->id,
            'schedule_id' => $schedule2->id,
            'sort_order'  => 0
        ]);
        $this->assertDatabaseHas('calendar_schedule', [
            'calendar_id' => $calendar->id,
            'schedule_id' => $schedule1->id,
            'sort_order'  => 1
        ]);
    }
}
