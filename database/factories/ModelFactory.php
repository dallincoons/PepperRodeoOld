<?php

use App\Item;
use App\Recipe;
use App\GroceryList;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'quantity' => $faker->randomFloat(),
        'name' => $faker->name,
        'isCheckedOff' => 0,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Recipe::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->text(15),
        'user_id' => App\User::all()->random()->id
    ];
});

$factory->define(App\GroceryList::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'user_id' => App\User::all()->random()->id
    ];
});

