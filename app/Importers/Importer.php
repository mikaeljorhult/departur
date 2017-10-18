<?php

namespace Departur\Importers;

use Carbon\Carbon;

interface Importer
{
    /**
     * Unique ID of importer.
     *
     * @return string
     */
    public function id();

    /**
     * Human-readable name of importer to be displayed to users.
     *
     * @return string
     */
    public function name();

    /**
     * Get events from calendar.
     *
     * @param string $calendar
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(string $calendar, Carbon $startDate, Carbon $endDate);
}
