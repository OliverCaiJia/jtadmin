<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Orm\SaasAccount::class, function (Faker $faker) {
    $total = mt_rand(501, 1000);
    $balance = mt_rand(1, 500);

    return [
        'account_name' => $faker->name,
        'total' => $total,
        'acc_charge' => mt_rand(500, 1000),
        'balance' => $balance,
        'frost' => $total - $balance
    ];
});
