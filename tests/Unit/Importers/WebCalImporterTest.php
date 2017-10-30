<?php

namespace Tests\Unit\Importers;

use Departur\Event;
use Departur\Importers\WebCalImporter;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WebCalImporterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Importer can be instantiated.
     *
     * @return void
     */
    public function testWebCalImporterCanBeInstantiated()
    {
        $importer = new WebCalImporter();
        $this->assertTrue(method_exists($importer, 'get'));
    }

    /**
     * Get method returns events from WebCal file.
     *
     * @return void
     */
    public function testEventsFromValidCalendarAreReturned()
    {
        $events = factory(Event::class, 2)->make()->sortBy('start_time');
        $webcal = view('tests.ical')->with('events', $events)->render();
        $this->mockHttpResponses([new Response(200, [], $webcal)]);

        $importer = new WebCalImporter();
        $returnedEvents = $importer->get('valid-webcal-url', now()->subYear(), now()->addYear());

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
     * Get method returns empty collection from empty WebCal file.
     *
     * @return void
     */
    public function testEmptyCollectionIsReturnedFromEmptyCalendar()
    {
        $webcal = view('tests.ical')->render();
        $this->mockHttpResponses([new Response(200, [], $webcal)]);

        $importer = new WebCalImporter();
        $returnedEvents = $importer->get('valid-webcal-url', now()->subYear(), now()->addYear());

        $this->assertInstanceOf(Collection::class, $returnedEvents);
        $this->assertCount(0, $returnedEvents);
    }

    /**
     * Get method throws an error if WebCal file is invalid.
     *
     * @expectedException \Departur\Exceptions\InvalidCalendarException
     *
     * @return void
     */
    public function testErrorIsThrownIfCalendarIsInvalid()
    {
        $this->mockHttpResponses([new Response(200, [], 'invalid-webcal')]);

        $importer = new WebCalImporter();
        $importer->get('valid-webcal-url', now()->subYear(), now()->addYear());
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
        $importer = new WebCalImporter();
        $importer->get('invalid-webcal-url', now()->subYear(), now()->addYear());
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
        $this->mockHttpResponses([new Response(404, [], 'webcal-not-found')]);

        $importer = new WebCalImporter();
        $importer->get('webcal-not-found-url', now()->subYear(), now()->addYear());
    }

    /**
     * Each requested calendar is cached by its URL for a couple of minutes to reduce number of HTTP requests.
     *
     * @return void
     */
    public function testResponsesAreCachedForMultipleRequests()
    {
        $events = factory(Event::class, 2)->make();
        $webcal = view('tests.ical')->with('events', $events)->render();
        $this->mockHttpResponses([
            new Response(200, [], $webcal),
            new Response(404, [], 'webcal-not-found'), // Will not be returned.
        ]);

        $importer = new WebCalImporter();
        $firstRetrieval = $importer->get('valid-webcal-url', now()->subYear(), now()->addYear());
        $secondRetrieval = $importer->get('valid-webcal-url', now()->subYear(), now()->addYear());

        $this->assertTrue(Cache::has('calendar-valid-webcal-url'));
        $this->assertEquals($firstRetrieval, $secondRetrieval);
    }

    /**
     * Each requested calendar is cached by its URL for a couple of minutes to reduce number of HTTP requests.
     *
     * @return void
     */
    public function testURLsAreRewrittenToUseHTTP()
    {
        $events = factory(Event::class, 2)->make();
        $webcal = view('tests.ical')->with('events', $events)->render();
        $this->mockHttpResponses([
            new Response(200, [], $webcal),
        ]);

        $importer = new WebCalImporter();
        $importer->get('webcal://localhost/calendar', now()->subYear(), now()->addYear());

        $this->assertTrue(Cache::has('calendar-http://localhost/calendar'));
    }
}
