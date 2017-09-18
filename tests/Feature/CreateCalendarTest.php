<?php

namespace Tests\Feature;

use Departur\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCalendarTest extends TestCase
{
    use RefreshDatabase;

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
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect('/calendars');
        $this->assertDatabaseHas('calendars', [
            'name' => 'Test Calendar',
            'url'  => 'http://example.com/calendar',
        ]);
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
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
            'url'  => 'http://example.com/calendar',
        ]);
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
            'url'        => 'not-a-url',
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
            'url'        => 'http://example.com/calendar',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('calendars', [
            'name' => 'Test Calendar',
        ]);
    }
}
