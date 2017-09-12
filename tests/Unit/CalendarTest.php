<?php

namespace Tests\Unit;

use Departur\Calendar;
use Departur\Event;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A calendar is active if start date has past but not end date.
     *
     * @return void
     */
    public function testCalendarIsActiveIfCurrentDateIsBetweenStartAndEndDate()
    {
        $activeCalendarA  = factory(Calendar::class)->states('active')->create();
        $activeCalendarB  = factory(Calendar::class)->states('active')->create();
        $inactiveCalendar = factory(Calendar::class)->states('inactive')->create();

        $calendars = Calendar::active()->get();

        $this->assertTrue($calendars->contains($activeCalendarA));
        $this->assertTrue($calendars->contains($activeCalendarB));
        $this->assertFalse($calendars->contains($inactiveCalendar));
    }

    /**
     * Events are imported to database through import method.
     *
     * @return void
     */
    public function testEventsAreImportedThroughImport()
    {
        $event = factory(Event::class)->make();
        $ical  = view('tests.ical')->with('events', [$event])->render();
        $this->mockHttpResponses([new Response(200, [], $ical)]);

        $calendar = factory(Calendar::class)->states('active')->create();
        $calendar->import();

        $this->assertDatabaseHas('events', [
            'title'       => $event->title,
            'location'    => $event->location,
            'description' => $event->description,
        ]);
    }
}
