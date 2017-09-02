<?php

use Faker\Generator as Faker;

$factory->define(Departur\Schedule::class, function (Faker $faker) {
    return [
        'name'         => $faker->words(4, true),
        'abbreviation' => $faker->unique()->bothify('????####'),
    ];
});
