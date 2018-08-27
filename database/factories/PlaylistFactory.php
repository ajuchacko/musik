<?php

use Faker\Generator as Faker;
use App\Playlist;

$factory->define(Playlist::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->firstName,
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
          },
    ];
});
