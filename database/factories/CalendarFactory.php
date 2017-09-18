<?php

use Carbon\Carbon;
use Departur\Calendar;
use Faker\Generator as Faker;

$factory->define(Calendar::class, function (Faker $faker) {
    $startDate = Carbon::instance($faker->dateTimeBetween('-1 year', '+1 year'));
    $endDate   = $startDate->copy()->addWeeks(rand(1, 26));

    return [
        'name'       => $faker->words(4, true),
        'start_date' => $startDate->format('Y-m-d'),
        'end_date'   => $endDate->format('Y-m-d'),
        'url'        => $faker->url,
    ];
});

$factory->state(Calendar::class, 'active', [
    'start_date' => Carbon::parse('-4 weeks'),
    'end_date'   => Carbon::parse('+4 weeks'),
]);

$factory->state(Calendar::class, 'inactive', [
    'start_date' => Carbon::parse('-5 weeks'),
    'end_date'   => Carbon::parse('-1 weeks'),
]);
