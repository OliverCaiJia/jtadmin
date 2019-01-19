<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Orm\SaasAuth::class, function (Faker $faker) {
    return [
        'accessToken' => str_random(10),
        'activated' => mt_rand(0, 1),
        'username' => $faker->name,
        'short_username' => $faker->word,
        'mobile' => $faker->phoneNumber
    ];
});
