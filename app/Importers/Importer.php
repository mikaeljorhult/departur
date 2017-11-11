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
     * Validation rules to be applied when a calendar is stored or updated.
     *
     * @return array
     */
    public function rules();

    /**
     * Get events from calendar.
     *
     * @param string         $calendar
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(string $calendar, Carbon $startDate, Carbon $endDate);
}
