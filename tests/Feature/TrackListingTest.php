<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Track;
use App\Album;

class TrackListingTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function users_can_view_tracks()
  {
    $album = factory(Album::class)->create();
    $track = factory(Track::class)->create([
      'title' => 'sample track title',
      'duration' => '04.2',
      'album_id' => $album->id
    ]);

    $album->tracks()->save($track);

    $response = $this->get("albums/{$album->id}");

    $response->assertOk();
    $response->assertSee('sample track title');
    $response->assertSee('4.2');
  }
}
