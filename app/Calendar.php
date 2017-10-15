<?php

namespace Departur;

use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Departur\Importers\ICalImporter;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use FormAccessible;

    /**
     * The attributes that are available for mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'url',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /**
     * Return related schedules.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class);
    }

    /**
     * Return related events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Format start date when displayed in form.
     *
     * @param string $value
     *
     * @return string
     */
    public function formStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Format end date when displayed in form.
     *
     * @param string $value
     *
     * @return string
     */
    public function formEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Return active calendars.
     *
     * @param $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        $today = Carbon::today();

        return $query->where('start_date', '<=', $today)
                     ->where('end_date', '>=', $today);
    }

    public function import()
    {
        $importer = new ICalImporter();

        try {
            $this->events()->saveMany($importer->get($this->url, $this->start_date, $this->end_date));
        } catch (\Exception $e) {
            dd('ERROR', $e);
        }
    }
}
