<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Que;
use App\Track;
use App\Album;
use App\Exceptions\TrackAlreadyAddedException;

class QueTest extends TestCase
{
  use RefreshDatabase;

  public function setUp()
  {
    parent::setUp();
  }
  /** @test */
  function tracks_can_be_qued()
  {
    $album = factory(Album::class)->create();

    $tracks = Collect([
        factory(Track::class)->make(['album_id' => $album->id]),
        factory(Track::class)->make(['title' => 'second track', 'album_id' => $album->id]),
        factory(Track::class)->make(['title' => 'third track', 'album_id' => $album->id]),
    ]);

    $q = new Que;
    $q->queTracks($tracks);

    $this->assertTrue(session()->has('tracks'));
    $this->assertCount(3, session('tracks.que'));
  }

  /** @test */
  function albums_can_be_qued()
  {
    $album = factory(Album::class)->create();
    $tracks = Collect([
        factory(Track::class)->create(['title' => 'first track',  'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'second track', 'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'third track',  'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'fourth track', 'album_id' => $album->id]),
    ]);

    $q = new Que;
    $tracks = $album->tracks->toArray();
    $q->queTracks($tracks);

    $this->assertTrue(session()->has('tracks'));
    $this->assertCount(4, session('tracks.que'));
  }

  /** @test */
  function add_tracks_to_already_created_que()
  {
    $album = factory(Album::class)->create();
    $tracks = Collect([
        factory(Track::class)->create(['title' => 'first track',  'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'second track', 'album_id' => $album->id]),
    ]);

    $q = new Que;
    $tracks = $album->tracks->toArray();
    $q->queTracks($tracks);
    $tracks = Collect([
      factory(Track::class)->make(['title' => 'third track',  'album_id' => $album->id]),
      factory(Track::class)->make(['title' => 'fourth track', 'album_id' => $album->id]),
    ]);
    $q->updateQue($tracks->toArray());
    $this->assertTrue(session()->has('tracks'));
    $this->assertCount(4, session('tracks.que'));
  }

  /** @test */
  function cannot_add_tracks_that_are_already_in_que()
  {
    $album = factory(Album::class)->create();
    $tracks = Collect([
        factory(Track::class)->create(['title' => 'same track',  'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'second track', 'album_id' => $album->id]),
    ]);

    $q = new Que;
    $tracks = $album->tracks->toArray();
    $q->queTracks($tracks);
    $tracks = Collect([
      factory(Track::class)->make(['title' => 'same track',  'album_id' => $album->id]),
      factory(Track::class)->make(['title' => 'fourth track', 'album_id' => $album->id]),
    ]);
    // dd(session()->all());
    try {
      $q->updateQue($tracks->toArray());

    } catch(TrackAlreadyAddedException $e) {
      // dd(session()->all());
      $this->assertTrue(session()->has('tracks'));
      $this->assertCount(2, session('tracks.que'));
      return ;
    }
    $this->fail('Track Added even though it was already in the que.');
  }

  /** @test */
  function a_track_can_be_removed()
  {
    $album = factory(Album::class)->create();
    $tracks = Collect([
        factory(Track::class)->create(['title' => 'first track remove',  'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'second track', 'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'third track',  'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'fourth track', 'album_id' => $album->id]),
    ]);

    $q = new Que;
    $tracksArray = $album->tracks->toArray();
    $q->queTracks($tracksArray);
    $this->assertTrue(session()->has('tracks'));
    $this->assertCount(4, session('tracks.que'));

    $track = $tracks->first();
    $q->remove($track->title);

    $this->assertCount(3, session('tracks.que'));
    $this->assertFalse(in_array($track->title, session('tracks.que')));
  }

  /** @test */
  function can_reset_que()
  {
    $album = factory(Album::class)->create();
    $tracks = Collect([
        factory(Track::class)->create(['title' => 'first track remove',  'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'second track', 'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'third track',  'album_id' => $album->id]),
        factory(Track::class)->create(['title' => 'fourth track', 'album_id' => $album->id]),
    ]);

    $q = new Que;
    $tracksArray = $album->tracks->toArray();
    $q->queTracks($tracksArray);
    $this->assertCount(4, session('tracks.que'));

    $q->reset();

    $this->assertNull(session('tracks'));
  }

  /** @test */
  function can_add_array_of_tracks_to_session()
  {
    $q = new Que;
    $array = [
      'first',
      'second',
      'third',
      'fourth'
    ];
    $q->queItems($array);

    $this->assertNotNull(session('tracks'));
    $this->assertCount(4, session('tracks.que'));
  }
}
