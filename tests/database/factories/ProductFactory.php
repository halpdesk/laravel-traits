<?php

use Faker\Generator as Faker;
use Halpdesk\LaravelTraits\Tests\Models\Product;

$factory->define(Product::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
    ];
});
