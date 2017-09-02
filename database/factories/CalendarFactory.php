<?php

use Faker\Generator as Faker;

$factory->define(Departur\Calendar::class, function (Faker $faker) {
    $startDate = \Carbon\Carbon::instance($faker->dateTimeBetween('-1 year', '+1 year'));
    $endDate   = $startDate->copy()->addWeeks(rand(1, 52));

    return [
        'name'       => $faker->words(4, true),
        'start_date' => $startDate,
        'end_date'   => $endDate,
        'url'        => $faker->url,
    ];
});
