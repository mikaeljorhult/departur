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
        $this->app->singleton('importers-ical', function () {
           return new \Departur\Importers\ICalImporter();
        });

        $this->app->singleton('importers-webcal', function () {
            return new \Departur\Importers\WebCalImporter();
        });

        $this->app->tag(['importers-ical', 'importers-webcal'], 'importers');
    }
}
