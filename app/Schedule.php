<?php

namespace Departur;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * The attributes that are available for mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * Return related calendars according to position.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function calendars()
    {
        return $this->belongsToMany(Calendar::class);
    }

    /**
     * Return events related to attached calendars.
     *
     * @return \Illuminate\Support\Collection
     */
    public function events()
    {
        if ($this->calendars->count() > 0) {
            $events = Event::whereIn('calendar_id', $this->calendars->pluck('id'))
                           ->orderBy('start_time')
                           ->get();
        }

        return isset($events) ? $events : collect();
    }

    /**
     * Return events related to attached calendars.
     * Convenience function to allow same API as with relationships.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEventsAttribute()
    {
        return $this->events();
    }

    /**
     * Make slug attribute lowercase when set.
     *
     * @param string $value
     *
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = strtolower($value);
    }

    /**
     * Return schedules with active calendars.
     *
     * @param $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereHas('calendars', function ($query) {
            $query->active();
        });
    }
}
