<?php

namespace Tests\Unit\Importers;

use Carbon\Carbon;
use Departur\Event;
use Departur\Importers\ICalImporter;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $importer = new ICalImporter('valid-ical-url', Carbon::today(), Carbon::tomorrow());
        $this->assertTrue(method_exists($importer, 'get'));
    }

    /**
     * Get method returns events from iCal file.
     *
     * @return void
     */
    public function testEventsFromValidICalAreReturned()
    {
        $events = factory(Event::class, 2)->make();
        $ical   = view('tests.ical')->with('events', $events)->render();
        $this->mockHttpResponses([new Response(200, [], $ical)]);

        $importer       = new ICalImporter('valid-ical-url', Carbon::now()->subYear(), Carbon::now()->addYear());
        $returnedEvents = $importer->get();

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
    public function testEmptyCollectionIsReturnedFromEmptyICal()
    {
        $ical = view('tests.ical')->render();
        $this->mockHttpResponses([new Response(200, [], $ical)]);

        $importer       = new ICalImporter('valid-ical-url', Carbon::now()->subYear(), Carbon::now()->addYear());
        $returnedEvents = $importer->get();

        $this->assertInstanceOf(Collection::class, $returnedEvents);
        $this->assertCount(0, $returnedEvents);
    }
}