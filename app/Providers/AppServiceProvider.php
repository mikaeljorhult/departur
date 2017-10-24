<?php

namespace Departur\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Allow foreign keys with SQLite.
        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::statement(DB::raw('PRAGMA foreign_keys=1'));
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // iCal
        $this->app->singleton('importers-ical', function () {
           return new \Departur\Importers\ICalImporter();
        });

        $this->app->tag('importers-ical', 'importers');

        // WebCal
        $this->app->singleton('importers-webcal', function () {
            return new \Departur\Importers\WebCalImporter();
        });

        $this->app->tag('importers-webcal', 'importers');

        // Google Calendar
        if (env('GOOGLE_API_KEY') !== null) {
            $this->app->singleton('importers-google-calendar', function () {
                return new \Departur\Importers\GoogleCalendarImporter();
            });

            $this->app->tag('importers-google-calendar', 'importers');
        }
    }
}
