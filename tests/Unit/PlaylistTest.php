<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Album;
use App\User;
use App\Track;
use App\Playlist;
use Carbon\Carbon;

class PlaylistTest extends TestCase
{
  use RefreshDatabase;

    /** @test */
    function tracks_can_add_to_playlist()
    {
      $album = factory(Album::class)->create([
        'title' => 'AlbumA',
        'released_by' => 'album A',
        'released_on' => Carbon::parse('June 25 2018'),
      ]);
      $trackA = factory(Track::class)->create([ 'title' => 'sample track 1', 'album_id' => $album->id]);
      $trackB = factory(Track::class)->create([ 'title' => 'sample track 2', 'album_id' => $album->id]);
      $trackC = factory(Track::class)->create([ 'title' => 'sample track 3', 'album_id' => $album->id]);

      $user = factory(User::class)->create();

      $playlist = Playlist::create([
        'title' => 'playlistA',
        'user_id' => $user->id
      ]);

      $playlist->addTracks($trackA);

      $tracks = $playlist->tracks->fresh();

      $this->assertTrue($tracks->contains($trackA));
      $this->assertFalse($tracks->contains($trackB));
      $this->assertFalse($tracks->contains($trackC));
      $this->assertEquals(1, $playlist->tracks->fresh()->count());
    }
}
