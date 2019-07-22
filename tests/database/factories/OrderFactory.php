<?php

use Faker\Generator as Faker;
use Halpdesk\LaravelTraits\Tests\Models\Order;

$factory->define(Order::class, function (Faker $faker) {
    return [
        "company_id"            => 1,
        "order_number"          => $faker->numberBetween(4000,8000),
    ];
});
