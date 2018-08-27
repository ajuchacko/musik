<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Album::class, function (Faker $faker) {
    return [
      // 'title' => $faker->name,
      'title' => 'Example Album Title',
      'released_by' => 'Example Corp',
      'released_on' => Carbon::parse('+2 weeks'),
      'image' => $faker->imageUrl('cats', true),
    ];
});
