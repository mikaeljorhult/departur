<?php

use Departur\Schedule;
use Faker\Generator as Faker;

$factory->define(Schedule::class, function (Faker $faker) {
    return [
        'name' => $faker->words(4, true),
        'slug' => $faker->unique()->slug(),
    ];
});
