<?php

namespace Departur\Importers;

use Carbon\Carbon;

interface Importer
{
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
