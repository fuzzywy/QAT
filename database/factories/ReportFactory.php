<?php

use Faker\Generator as Faker;

$factory->define(\App\Report::class, function (Faker $faker) {
    return [
        'taskName' => $faker->text(),
        'createTime' => $faker->dateTime(),
        'reportFreq' => $faker->text(),
        'template' => $faker->text(),
        'executeTime' => $faker->dateTime(),
        'status' => $faker->randomElement(['Running', 'Stopped'])
    ];
});
