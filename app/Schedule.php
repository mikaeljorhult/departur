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
        return $this->belongsToMany(\Departur\Calendar::class);
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
