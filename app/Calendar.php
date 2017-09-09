<?php

namespace Departur;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
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
        return $this->belongsToMany(\Departur\Schedule::class);
    }

    /**
     * Return related events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function events()
    {
        return $this->hasMany(\Departur\Event::class);
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
        $today = \Carbon\Carbon::today();

        return $query->where('start_date', '<=', $today)
                     ->where('end_date', '>=', $today);
    }
}
