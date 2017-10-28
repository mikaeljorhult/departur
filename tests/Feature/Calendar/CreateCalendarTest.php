<?php

namespace Tests\Feature;

use Departur\Calendar;
use Departur\Jobs\ImportCalendar;
use Departur\Schedule;
use Departur\User;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCalendarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Fake Queue facade for all test to avoid running any dispatched jobs.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Queue::fake();
    }

    /**
     * A user can create a calendar.
     *
     * @return void
     */
    public function testUserCanCreateCalendar()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'end_date'   => '2017-12-01',
            'type'       => 'ical',
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect('/calendars');
        $this->assertDatabaseHas('calendars', [
            'name' => 'Test Calendar',
            'url'  => 'http://example.com/calendar',
        ]);
        Queue::assertPushed(ImportCalendar::class);
    }

    /**
     * A visitor can not create a calendar.
     *
     * @return void
     */
    public function testVisitorCanNotCreateCalendar()
    {
        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'end_date'   => '2017-12-01',
            'type'       => 'ical',
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
            'url'  => 'http://example.com/calendar',
        ]);
        Queue::assertNotPushed(ImportCalendar::class);
    }

    /**
     * Calendars must have a name.
     *
     * @return void
     */
    public function testCalendarMustHaveAName()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/calendars', [
            'start_date' => '2017-01-01',
            'end_date'   => '2017-12-01',
            'type'       => 'ical',
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'url' => 'http://example.com/calendar',
        ]);
    }

    /**
     * Calendars must have a URL.
     *
     * @return void
     */
    public function testCalendarMustHaveAURL()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'end_date'   => '2017-12-01',
            'type'       => 'ical'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
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

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'end_date'   => '2017-12-01',
            'type'       => 'ical',
            'url'        => 'not-a-url',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
        ]);
    }

    /**
     * Calendars must have a URL.
     *
     * @return void
     */
    public function testCalendarMustHaveAType()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'end_date'   => '2017-12-01',
            'type'       => '',
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
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

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'end_date'   => '2017-12-01',
            'type'       => 'not-valid-type',
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
        ]);
    }

    /**
     * Calendars must have a start date.
     *
     * @return void
     */
    public function testCalendarMustHaveAStartDate()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/calendars', [
            'name'     => 'Test Calendar',
            'end_date' => '2017-12-01',
            'type'       => 'ical',
            'url'      => 'http://example.com/calendar',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
        ]);
    }

    /**
     * Calendar start date must be valid a valid date.
     *
     * @return void
     */
    public function testStartDateMustBeValidDate()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => 'not-a-date',
            'end_date'   => '2017-12-01',
            'type'       => 'ical',
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
        ]);
    }

    /**
     * Calendars must have a end date.
     *
     * @return void
     */
    public function testCalendarMustHaveAEndDate()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'type'       => 'ical',
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
        ]);
    }

    /**
     * Calendar end date must be valid a valid date.
     *
     * @return void
     */
    public function testEndDateMustBeValidDate()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'end_date'   => 'not-a-date',
            'type'       => 'ical',
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
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

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-12-01',
            'end_date'   => '2017-01-01',
            'type'       => 'ical',
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
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

        $schedule = factory(Schedule::class)->create();

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'end_date'   => '2017-12-01',
            'type'       => 'ical',
            'url'        => 'http://example.com/calendar',
            'schedules'  => [$schedule->id]
        ]);

        $response->assertRedirect('/calendars');
        $this->assertDatabaseHas('calendar_schedule', [
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

        $schedules = factory(Schedule::class, 2)->create();
        $schedules[0]->calendars()->attach(factory(Calendar::class)->create());

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'end_date'   => '2017-12-01',
            'type'       => 'ical',
            'url'        => 'http://example.com/calendar',
            'schedules'  => [$schedules[1]->id, $schedules[0]->id]
        ]);

        $response->assertRedirect('/calendars');
        $this->assertDatabaseHas('calendar_schedule', [
            'schedule_id' => $schedules[1]->id,
            'sort_order'  => 0,
        ]);
        $this->assertDatabaseHas('calendar_schedule', [
            'schedule_id' => $schedules[0]->id,
            'sort_order'  => 1,
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

        $response = $this->post('/calendars', [
            'name'       => 'Test Calendar',
            'start_date' => '2017-01-01',
            'end_date'   => '2017-12-01',
            'type'       => 'ical',
            'url'        => 'http://example.com/calendar',
            'schedules'  => [100]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendar_schedule', [
            'schedule_id' => 100,
        ]);
    }
}
