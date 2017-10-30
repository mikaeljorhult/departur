<?php

use Faker\Generator as Faker;

$factory->define(Departur\Event::class, function (Faker $faker) {
    $startDate = \Carbon\Carbon::instance($faker->dateTimeBetween('-1 week', '+1 week'));
    $endDate = $startDate->copy()->addHours(rand(1, 3));

    return [
        'calendar_id' => factory(\Departur\Calendar::class)->lazy(),
        'title'       => $faker->words(4, true),
        'location'    => $faker->words(2, true),
        'description' => $faker->sentence,
        'start_time'  => $startDate,
        'end_time'    => $endDate,
    ];
});
