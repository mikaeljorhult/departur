<?php

namespace Departur\Importers;

use Carbon\Carbon;
use Departur\Event;
use Departur\Exceptions\InvalidCalendarException;
use Departur\Exceptions\UnreachableCalendarException;
use GuzzleHttp\Client;
use ICal\ICal;
use Illuminate\Support\Facades\Cache;

class ICalImporter implements Importer
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
     * Unique ID of importer.
     *
     * @return string
     */
    public function id()
    {
        return 'ical';
    }

    /**
     * Human-readable name of importer to be displayed to users.
     *
     * @return string
     */
    public function name()
    {
        return 'iCal';
    }

    /**
     * Validation rules to be applied when a calendar is stored or updated.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => ['required', 'url'],
        ];
    }

    /**
     * Get events from calendar.
     *
     * @param string         $calendar
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(string $calendar, Carbon $startDate, Carbon $endDate)
    {
        // Set variables.
        $this->calendar = $calendar;
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        // Retrieve URL.
        $body = $this->request()->getBody();

        // Validate retrieved content.
        $this->validate($body);

        // Return collection of events.
        return $this->parse($body);
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
     * @throws \Departur\Exceptions\UnreachableCalendarException
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    private function request()
    {
        $url = $this->url();

        return Cache::remember('calendar-'.$url, 10, function () use ($url) {
            $client = app(Client::class);

            try {
                $response = $client->get($url);
            } catch (\Exception $exception) {
                throw new UnreachableCalendarException('Calendar is not reachable.');
            }

            return $response;
        });
    }

    /**
     * @param string $body
     *
     * @throws \Departur\Exceptions\InvalidCalendarException
     *
     * @return bool
     */
    private function validate(string $body)
    {
        if (str_contains($body, 'BEGIN:VCALENDAR') === false) {
            throw new InvalidCalendarException('Calendar is not a valid iCal.');
        }

        return true;
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
        $calendar = new ICal($body);

        return collect($calendar->eventsFromRange($this->startDate, $this->endDate))->map(function ($event) {
            return new Event([
                'title'       => substr($event->summary, 0, 255),
                'location'    => substr($event->location, 0, 255),
                'description' => $event->description,
                'start_time'  => Carbon::createFromTimestamp($event->dtstart_array[2]),
                'end_time'    => Carbon::createFromTimestamp($event->dtend_array[2]),
            ]);
        });
    }
}
