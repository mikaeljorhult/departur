<?php

namespace Tests\Unit\Importers;

use Departur\Event;
use Departur\Importers\ICalImporter;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ICalImporterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Importer can be instantiated.
     *
     * @return void
     */
    public function testICalImporterCanBeInstantiated()
    {
        $importer = new ICalImporter();
        $this->assertTrue(method_exists($importer, 'get'));
    }

    /**
     * Get method returns events from iCal file.
     *
     * @return void
     */
    public function testEventsFromValidCalendarAreReturned()
    {
        $events = factory(Event::class, 2)->make()->sortBy('start_time');
        $ical = view('tests.ical')->with('events', $events)->render();
        $this->mockHttpResponses([new Response(200, [], $ical)]);

        $importer = new ICalImporter();
        $returnedEvents = $importer->get('valid-ical-url', now()->subYear(), now()->addYear());

        $this->assertInstanceOf(Collection::class, $returnedEvents);
        $this->assertCount(2, $returnedEvents);
        $this->assertEquals($events->first()->title, $returnedEvents->first()->title);
        $this->assertEquals($events->first()->location, $returnedEvents->first()->location);
        $this->assertEquals($events->first()->description, $returnedEvents->first()->description);
        $this->assertEquals($events->last()->title, $returnedEvents->last()->title);
        $this->assertEquals($events->last()->location, $returnedEvents->last()->location);
        $this->assertEquals($events->last()->description, $returnedEvents->last()->description);
    }

    /**
     * Get method returns empty collection from empty iCal file.
     *
     * @return void
     */
    public function testEmptyCollectionIsReturnedFromEmptyCalendar()
    {
        $ical = view('tests.ical')->render();
        $this->mockHttpResponses([new Response(200, [], $ical)]);

        $importer = new ICalImporter();
        $returnedEvents = $importer->get('valid-ical-url', now()->subYear(), now()->addYear());

        $this->assertInstanceOf(Collection::class, $returnedEvents);
        $this->assertCount(0, $returnedEvents);
    }

    /**
     * Get method throws an error if iCal file is invalid.
     *
     * @expectedException \Departur\Exceptions\InvalidCalendarException
     *
     * @return void
     */
    public function testErrorIsThrownIfICalendarIsInvalid()
    {
        $this->mockHttpResponses([new Response(200, [], 'invalid-ical')]);

        $importer = new ICalImporter();
        $importer->get('valid-ical-url', now()->subYear(), now()->addYear());
    }

    /**
     * Get method throws an error if URL is invalid.
     *
     * @expectedException \Departur\Exceptions\UnreachableCalendarException
     *
     * @return void
     */
    public function testErrorIsThrownIfURLIsInvalid()
    {
        $importer = new ICalImporter();
        $importer->get('invalid-ical-url', now()->subYear(), now()->addYear());
    }

    /**
     * Get method throws an error if URL is not found.
     *
     * @expectedException \Departur\Exceptions\UnreachableCalendarException
     *
     * @return void
     */
    public function testErrorIsThrownIfURLNotFound()
    {
        $this->mockHttpResponses([new Response(404, [], 'ical-not-found')]);

        $importer = new ICalImporter();
        $importer->get('ical-not-found-url', now()->subYear(), now()->addYear());
    }

    /**
     * Each requested calendar is cached by its URL for a couple of minutes to reduce number of HTTP requests.
     *
     * @return void
     */
    public function testResponsesAreCachedForMultipleRequests()
    {
        $events = factory(Event::class, 2)->make();
        $ical = view('tests.ical')->with('events', $events)->render();
        $this->mockHttpResponses([
            new Response(200, [], $ical),
            new Response(404, [], 'ical-not-found'), // Will not be returned.
        ]);

        $importer = new ICalImporter();
        $firstRetrieval = $importer->get('valid-ical-url', now()->subYear(), now()->addYear());
        $secondRetrieval = $importer->get('valid-ical-url', now()->subYear(), now()->addYear());

        $this->assertTrue(Cache::has('calendar-valid-ical-url'));
        $this->assertEquals($firstRetrieval, $secondRetrieval);
    }
}
