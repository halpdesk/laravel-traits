<?php

use Faker\Generator as Faker;
use Halpdesk\LaravelTraits\Tests\Models\Company;

$factory->define(Company::class, function (Faker $faker) {
    return [
        "company_name"  => $faker->company,
        "email"         => $faker->safeEmail,
        "registered_at" => $faker->date('now'),
    ];
});
