<?php

namespace Departur\Importers;

use Carbon\Carbon;
use Departur\Event;
use GuzzleHttp\Client;
use Sabre\VObject\Reader;

class ICalImporter
{
    /**
     * URL to calendar.
     *
     * @var string
     */
    private $calendar;

    /**
     * Timestamp from which calendar events should be collected.
     * Used when expanding recurring events.
     *
     * @var \Carbon\Carbon
     */
    private $startDate;

    /**
     * Timestamp to which calendar events should be collected.
     * Used when expanding recurring events.
     *
     * @var \Carbon\Carbon
     */
    private $endDate;

    /**
     * Constructor.
     *
     * @param string $calendar
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     */
    public function __construct(string $calendar, Carbon $startDate, Carbon $endDate)
    {
        $this->calendar  = $calendar;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    /**
     * Get events from calendar.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        return $this->parse((string)$this->request()->getBody());
    }

    /**
     * Return URL to calendar.
     *
     * @return string
     */
    private function url()
    {
        return $this->calendar;
    }

    /**
     * Retrieve URL for calendar.
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    private function request()
    {
        $client   = app(Client::class);
        $response = $client->get($this->url());

        return $response;
    }

    /**
     * Parse response body and return events.
     *
     * @param string $body
     *
     * @return \Illuminate\Support\Collection
     */
    private function parse(string $body)
    {
        $events   = collect();
        $calendar = Reader::read($body, Reader::OPTION_FORGIVING)
                          ->expand($this->startDate, $this->endDate);

        // Add all events to array.
        if (count($calendar->VEVENT) > 0) {
            foreach ($calendar->VEVENT as $item) {
                $events[] = new Event([
                    'title'       => substr((string)$item->SUMMARY, 0, 255),
                    'location'    => substr((string)$item->LOCATION, 0, 255),
                    'description' => (string)$item->DESCRIPTION,
                    'start_time'  => $item->DTSTART->getDateTime(),
                    'end_time'    => $item->DTEND->getDateTime()
                ]);
            }
        }

        return $events;
    }
}
