<?php

namespace Departur\Importers;

use Carbon\Carbon;
use Departur\Event;
use Departur\Exceptions\InvalidCalendarException;
use Departur\Exceptions\UnreachableCalendarException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class GoogleCalendarImporter implements Importer
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
        return 'google-calendar';
    }

    /**
     * Human-readable name of importer to be displayed to users.
     *
     * @return string
     */
    public function name()
    {
        return 'Google Calendar';
    }

    /**
     * Get events from calendar.
     *
     * @param string $calendar
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(string $calendar, Carbon $startDate, Carbon $endDate)
    {
        // Set variables.
        $this->calendar  = $calendar;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;

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
        $url = 'https://www.googleapis.com/calendar/v3/calendars/' . $this->calendar . '/events';

        $parameters = [
            'singleEvents' => 'true',
            'timeMin'      => $this->startDate->format('Y-m-d') . 'T00:00:00.000Z',
            'timeMax'      => $this->endDate->format('Y-m-d') . 'T23:59:59.000Z',
            'orderBy'      => 'startTime',
            'maxResults'   => '500',
            'key'          => env('GOOGLE_API_KEY')
        ];

        return $url . '?' . http_build_query($parameters);
    }

    /**
     * Retrieve URL for calendar.
     *
     * @return \GuzzleHttp\Psr7\Response
     * @throws \Departur\Exceptions\UnreachableCalendarException
     */
    private function request()
    {
        $url = $this->url();

        return Cache::remember('calendar-' . $url, 10, function () use ($url) {
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
     * @return bool
     * @throws \Departur\Exceptions\InvalidCalendarException
     */
    private function validate(string $body)
    {
        if (str_contains($body, 'calendar#events') === false) {
            throw new InvalidCalendarException('Calendar is not a valid Google Calendar.');
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
        $json = json_decode($body, true);

        return collect($json['items'])->map(function ($event) {
            return new Event([
                'title'       => isset($event['summary']) ? substr($event['summary'], 0, 255) : '',
                'location'    => isset($event['location']) ? substr($event['location'], 0, 255) : '',
                'description' => isset($event['description']) ? $event['description'] : '',
                'start_time'  => isset($event['start']['dateTime']) ? Carbon::parse($event['start']['dateTime']) : Carbon::parse($event['start']['date'] . 'T00:00:00'),
                'end_time'    => isset($event['end']['dateTime']) ? Carbon::parse($event['end']['dateTime']) : Carbon::parse($event['start']['date'] . 'T00:00:00')
            ]);
        });
    }
}
