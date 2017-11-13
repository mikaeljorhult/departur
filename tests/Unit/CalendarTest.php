<?php

namespace Tests\Unit;

use Departur\Calendar;
use Departur\Event;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $activeCalendarA = factory(Calendar::class)->states('active')->create();
        $activeCalendarB = factory(Calendar::class)->states('active')->create();
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
        $ical = view('tests.ical')->with('events', [$event])->render();
        $this->mockHttpResponses([new Response(200, [], $ical)]);

        $calendar = factory(Calendar::class)->states('active')->create();
        $calendar->import();

        $this->assertDatabaseHas('events', [
            'title'       => $event->title,
            'location'    => $event->location,
            'description' => $event->description,
        ]);
    }

    /**
     * Events are deleted before new ones are imported.
     *
     * @return void
     */
    public function testEventsAreClearedBeforeNewImport()
    {
        $event = factory(Event::class)->make();
        $ical = view('tests.ical')->with('events', [$event])->render();
        $this->mockHttpResponses([new Response(200, [], $ical)]);

        $calendar = factory(Calendar::class)->states('active')->create();

        // Import multiple times.
        $calendar->import();
        $calendar->import();

        $this->assertCount(1, $calendar->events);
        $this->assertEquals($event->name, $calendar->events->first()->name);
        $this->assertEquals($event->description, $calendar->events->first()->description);
    }
}
