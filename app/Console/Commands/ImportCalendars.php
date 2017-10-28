<?php

namespace Departur\Console\Commands;

use Departur\Calendar;
use Departur\Jobs\ImportCalendar;
use Illuminate\Console\Command;

class ImportCalendars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'departur:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all active calendars.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all active calendars.
        $activeCalendars = Calendar::active()->get();

        // Dispatch an import job for each calendar.
        $activeCalendars->each(function ($item, $key) {
            dispatch(new ImportCalendar($item));
        });
    }
}
