<?php

namespace Tests\Unit;

use Departur\Calendar;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A calendar is active if start date has past but not end date.
     *
     * @return void
     */
    public function testCalendarIsActiveIfCurrentDateIsBetweenStartAndEndDate()
    {
        $activeCalendarA  = factory(Calendar::class)->states('active')->create();
        $activeCalendarB  = factory(Calendar::class)->states('active')->create();
        $inactiveCalendar = factory(Calendar::class)->states('inactive')->create();

        $calendars = Calendar::active()->get();

        $this->assertTrue($calendars->contains($activeCalendarA));
        $this->assertTrue($calendars->contains($activeCalendarB));
        $this->assertFalse($calendars->contains($inactiveCalendar));
    }
}
