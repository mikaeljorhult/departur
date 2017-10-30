<?php

namespace Tests\Unit\Importers;

use Departur\Event;
use Departur\Importers\GoogleCalendarImporter;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GoogleCalendarImporterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Importer can be instantiated.
     *
     * @return void
     */
    public function testGoogleCalendarImporterCanBeInstantiated()
    {
        $importer = new GoogleCalendarImporter();
        $this->assertTrue(method_exists($importer, 'get'));
    }

    /**
     * Get method returns events from Google Calendar results.
     *
     * @return void
     */
    public function testEventsFromValidCalendarAreReturned()
    {
        $events = factory(Event::class, 2)->make()->sortBy('start_time');
        $googleCal = view('tests.google')->with('events', $events)->render();
        $this->mockHttpResponses([new Response(200, [], $googleCal)]);

        $importer = new GoogleCalendarImporter();
        $returnedEvents = $importer->get('valid-google-url', now()->subYear(), now()->addYear());

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
     * Get method returns empty collection from empty Google Calendar results.
     *
     * @return void
     */
    public function testEmptyCollectionIsReturnedFromEmptyCalendar()
    {
        $googleCal = view('tests.google')->render();
        $this->mockHttpResponses([new Response(200, [], $googleCal)]);

        $importer = new GoogleCalendarImporter();
        $returnedEvents = $importer->get('valid-google-url', now()->subYear(), now()->addYear());

        $this->assertInstanceOf(Collection::class, $returnedEvents);
        $this->assertCount(0, $returnedEvents);
    }

    /**
     * Get method throws an error if Google Calendar is invalid.
     *
     * @expectedException \Departur\Exceptions\InvalidCalendarException
     *
     * @return void
     */
    public function testErrorIsThrownIfCalendarIsInvalid()
    {
        $this->mockHttpResponses([new Response(200, [], 'invalid-google')]);

        $importer = new GoogleCalendarImporter();
        $importer->get('valid-google-url', now()->subYear(), now()->addYear());
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
        $importer = new GoogleCalendarImporter();
        $importer->get('invalid-google-url', now()->subYear(), now()->addYear());
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
        $this->mockHttpResponses([new Response(404, [], view('tests.google-404')->render())]);

        $importer = new GoogleCalendarImporter();
        $importer->get('google-not-found-url', now()->subYear(), now()->addYear());
    }

    /**
     * Each requested calendar is cached by its URL for a couple of minutes to reduce number of HTTP requests.
     *
     * @return void
     */
    public function testResponsesAreCachedForMultipleRequests()
    {
        $events = factory(Event::class, 2)->make();
        $googleCal = view('tests.google')->with('events', $events)->render();
        $this->mockHttpResponses([
            new Response(200, [], $googleCal),
            new Response(404, [], view('tests.google-404')->render()), // Will not be returned.
        ]);

        $importer = new GoogleCalendarImporter();
        $firstRetrieval = $importer->get('valid-google-url', now()->subYear(), now()->addYear());
        $secondRetrieval = $importer->get('valid-google-url', now()->subYear(), now()->addYear());

        $this->assertEquals($firstRetrieval, $secondRetrieval);
    }
}
