<?php

namespace Tests\Unit\Importers;

use Departur\Event;
use Departur\Importers\ICalFileImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ICalFileImporterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Importer to be tested.
     *
     * @var
     */
    private static $importer;

    /**
     * Instantiate a new importer object.
     */
    public static function setUpBeforeClass()
    {
        self::$importer = new ICalFileImporter();
    }

    /**
     * Create an uploaded calendar file.
     *
     * @param string $body
     * @return string
     */
    private function createCalendarFile(string $body)
    {
        // Create file with supplied content.
        $filename = 'test-'.uniqid().'.ics';
        Storage::put($filename, $body);

        // Make sure test file is removed after test is run.
        $this->beforeApplicationDestroyed(function () {
            // Get the names of all files starting with "test-".
            $files = array_filter(Storage::files(), function ($filename) {
                return substr($filename, 0, 5) === 'test-';
            });

            Storage::delete($files);
        });

        return $filename;
    }

    /**
     * Delete an uploaded calendar file.
     *
     * @param string $filename
     */
    private function deleteCalendarFile(string $filename)
    {
        Storage::delete($filename);
    }

    /**
     * Importer can be instantiated.
     *
     * @return void
     */
    public function testICalFileImporterCanBeInstantiated()
    {
        $this->assertTrue(method_exists(self::$importer, 'get'));
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
        $filename = $this->createCalendarFile($ical);

        $returnedEvents = self::$importer->get($filename, now()->subYear(), now()->addYear());

        $this->assertInstanceOf(Collection::class, $returnedEvents);
        $this->assertCount(2, $returnedEvents);
        $this->assertEquals($events->first()->title, $returnedEvents->first()->title);
        $this->assertEquals($events->first()->location, $returnedEvents->first()->location);
        $this->assertEquals($events->first()->description, $returnedEvents->first()->description);
        $this->assertEquals($events->last()->title, $returnedEvents->last()->title);
        $this->assertEquals($events->last()->location, $returnedEvents->last()->location);
        $this->assertEquals($events->last()->description, $returnedEvents->last()->description);

        $this->deleteCalendarFile($filename);
    }

    /**
     * Get method returns empty collection from empty iCal file.
     *
     * @return void
     */
    public function testEmptyCollectionIsReturnedFromEmptyCalendar()
    {
        $ical = view('tests.ical')->render();
        $filename = $this->createCalendarFile($ical);

        $returnedEvents = self::$importer->get($filename, now()->subYear(), now()->addYear());

        $this->assertInstanceOf(Collection::class, $returnedEvents);
        $this->assertCount(0, $returnedEvents);

        $this->deleteCalendarFile($filename);
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
        $filename = $this->createCalendarFile('invalid-ical');

        self::$importer->get($filename, now()->subYear(), now()->addYear());

        // This will not run as exception is thrown.
        $this->deleteCalendarFile($filename);
    }

    /**
     * Get method throws an error if file name is not found.
     *
     * @expectedException \Departur\Exceptions\UnreachableCalendarException
     *
     * @return void
     */
    public function testErrorIsThrownIfFileNameNotFound()
    {
        self::$importer->get('not-found.ics', now()->subYear(), now()->addYear());
    }

    /**
     * Each requested calendar is cached by its file name for a couple of minutes to reduce number of reads.
     *
     * @return void
     */
    public function testResponsesAreCachedForMultipleRequests()
    {
        $events = factory(Event::class, 2)->make();
        $ical = view('tests.ical')->with('events', $events)->render();
        $filename = $this->createCalendarFile($ical);

        $firstRetrieval = self::$importer->get($filename, now()->subYear(), now()->addYear());
        $this->deleteCalendarFile($filename); // Delete file before second read.
        $secondRetrieval = self::$importer->get($filename, now()->subYear(), now()->addYear());

        $this->assertTrue(Cache::has('calendar-'.$filename));
        $this->assertEquals($firstRetrieval, $secondRetrieval);
    }
}
