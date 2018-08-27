<?php

use Faker\Generator as Faker;

$factory->define(App\Track::class, function (Faker $faker) {
    return [
      'title' => 'Aararo',
      'duration' => '252',  // 4.2
      'album_id' => function() {
          return factory(App\Album::class)->create()->id;
        },
      // 'filename' => 'sampletrackname.mp3',
    ];
});
