<?php

use Faker\Generator as Faker;

$factory->define(App\Item::class, function (Faker $faker) {
    return [
        'category_id' => $faker->numberBetween(1, 10),
        'name' => $faker->name,
        'description' => $faker->paragraph(10),
        'price' => $faker->randomFloat(2, 99, 9999),
        'quantity' => $faker->randomNumber(2)
    ];
});
