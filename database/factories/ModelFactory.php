<?php

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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'full_name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'password' => $password ?: $password = bcrypt('secret')
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'total' => $faker->numberBetween(1, 10),
        'price' => $faker->numberBetween(10, 100),
        'category_id' => $faker->randomElement(App\Category::pluck('id')->toArray()),
        'expired_day' => $faker->date
    ];
});

$factory->define(App\Order::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomElement(App\User::pluck('id')->toArray()),
        'status' => random_int(0, 3),
        'address' => $faker->address
    ];
});

$factory->define(App\OrderItem::class, function (Faker\Generator $faker) {
    return [
        'order_id' => $faker->randomElement(App\Order::pluck('id')->toArray()),
        'item_id' => $faker->randomElement(App\Item::pluck('id')->toArray()),
        'quantity' => random_int(1, 10),
        'price' => $faker->numberBetween(10, 100),
    ];
});

$factory->define(App\Payment::class, function (Faker\Generator $faker) {
    return [
        'order_id' => $faker->randomElement(App\Order::pluck('id')->toArray()),
        'transaction_id' =>  $faker->bothify('##???##???'),
        'payment_at' => $faker->dateTime(),
        'payment_gross' => 344555.1,
        'payer_email' => $faker->safeEmail,
    ];
});
