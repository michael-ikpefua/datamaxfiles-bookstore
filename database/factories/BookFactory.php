<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'isbn' => $faker->isbn10,
        'author_id' => factory(\App\User::class)->create()->getKey(),
        'country' => $faker->country,
        'number_of_pages' => $faker->randomDigitNotNull,
        'publishers' => $faker->company,
        'release_date' => now()
    ];
});
