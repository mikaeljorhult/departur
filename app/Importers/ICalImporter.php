<?php

namespace Departur\Importers;

use Departur\Event;
use GuzzleHttp\Client;
use Sabre\VObject\Reader;

class ICalImporter
{
    private $calendar;
    private $startDate;
    private $endDate;

    public function __construct($calendar, $startDate, $endDate)
    {
        $this->calendar  = $calendar;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function get()
    {
        $response = $this->request();

        if ($response) {
            return $this->parse((string)$response->getBody());
        } else {
            throw new \Exception('Calendar could not be retrieved.');
        }
    }

    private function url()
    {
        return $this->calendar;
    }

    private function request()
    {
        $client   = app(Client::class);
        $response = $client->get($this->url(), ['exceptions' => false]);

        if ($response->getStatusCode() === 200) {
            return $response;
        }

        return false;
    }

    private function parse($body)
    {
        if ($body) {
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

        return false;
    }
}
