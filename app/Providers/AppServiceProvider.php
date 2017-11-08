<?php

namespace Departur\Providers;

use Departur\Http\ViewComposers\ImporterComposer;
use Departur\Importers\GoogleCalendarImporter;
use Departur\Importers\ICalImporter;
use Departur\Importers\WebCalImporter;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
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
        if (DB::connection() instanceof SQLiteConnection) {
            DB::statement(DB::raw('PRAGMA foreign_keys=1'));
        }

        // Bind array of available importers to calendar views.
        View::composer('calendars._form', ImporterComposer::class);
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
            return new ICalImporter();
        });

        $this->app->tag('importers-ical', 'importers');

        // WebCal
        $this->app->singleton('importers-webcal', function () {
            return new WebCalImporter();
        });

        $this->app->tag('importers-webcal', 'importers');

        // Google Calendar
        if (env('GOOGLE_API_KEY') !== null) {
            $this->app->singleton('importers-google-calendar', function () {
                return new GoogleCalendarImporter();
            });

            $this->app->tag('importers-google-calendar', 'importers');
        }
    }
}
