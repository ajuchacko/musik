<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\Album;
use App\Track;
use App\Playlist;
use App\User;

class PlaylistCreationTest extends TestCase
{
  use RefreshDatabase;

  public function setUp()
  {
    parent::setUp();
    $this->album = factory(Album::class)->create([
      'title' => 'Playlist Album',
      'released_by' => 'Muzik247playlist',
      'released_on' => Carbon::parse('June 15 2018'),
    ]);
    $this->track1 = factory(Track::class, 3)->create([
      'title' => 'sample track 1',
      'duration' => '251',
      'album_id' => $this->album->id
    ]);
    $this->track2 = factory(Track::class)->create([
      'title' => 'sample track 2',
      'duration' => '252',
      'album_id' => $this->album->id
    ]);
    $this->track3 = factory(Track::class)->create([
      'title' => 'sample track 3',
      'duration' => '253',
      'album_id' => $this->album->id
    ]);
  }

  /** @test */
  function user_can_create_a_playlist()
  {
    $user = factory(User::class)->create();

    $response = $this->actingAs($user)
                     ->json('POST', 'playlists', [
                        'title' => 'first playlist'
                     ]);

    $response->assertStatus(201);
    $playlist = $user->playlists()->where('title', 'first playlist')->first();
    $this->assertNotNull($playlist);
    $this->assertEquals(1, $user->playlists()->count());
  }

  /** @test */
  function not_authorized_user_creating_playlist_redirects()
  {
    $response = $this->json('POST', 'playlists', [
                        'title' => 'not logged in user playlist'
                     ]);

    $response->assertStatus(401);
  }

  /** @test */
  function title_is_required_to_create_a_playlist()
  {
    $user = factory(User::class)->create();
    $response = $this->actingAs($user)
                     ->json('POST', 'playlists', []);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors('title');
  }

  /** @test */
  function tracks_can_be_added_to_playlist()
  {
    $this->withoutExceptionHandling();
    $user = factory(User::class)->create();
    $playlist = factory(Playlist::class)->create(['title' => 'PlaylistA','user_id' => $user->id]);

    $response = $this->actingAs($user)
                     ->json('POST', "addtracks/{$playlist->id}", [
                       'track' => $this->track2->id,
                     ]);
    $response->assertStatus(302);
    $playlist = $user->playlists()->where('title', 'PlaylistA')->first();
    $this->assertNotNull($playlist);
    $this->assertEquals(1, $playlist->tracks->count());
  }

  /** @test */
  function only_playlist_owning_user_can_add_tracks()
  {
    $strangeUser = factory(User::class)->create();
    $playlist = factory(Playlist::class)->create(['title' => 'PlaylistB']);
    $response = $this->actingAs($strangeUser)
                     ->json('POST', "addtracks/{$playlist->id}", [
                       'track' => $this->track3->id,
                     ]);
    $response->assertStatus(401);
    $this->assertEquals(0, $playlist->tracks()->count());
  }

  /** @test */
  function track_can_be_removed_from_playlist()
  {
    $user = factory(User::class)->create();
    $playlist = factory(Playlist::class)->create(['title' => 'PlaylistA','user_id' => $user->id]);
    $playlist->tracks()->sync($this->track1);
    $this->assertCount(3, $playlist->tracks);

    $response = $this->actingAs($user)
                     ->json('POST', "removetracks/{$playlist->id}", [
                       'track' => $this->track1->first()->id,
                     ]);

    $response->assertStatus(204);
    $this->assertDatabaseMissing('playlist_track', ['track_id' => $this->track1->first()->id]);
    $this->assertEquals(2, $playlist->tracks()->count());
  }

  /** @test */
  function only_playlist_owning_user_can_remove_tracks()
  {
    $strangeUser = factory(User::class)->create();
    $playlist = factory(Playlist::class)->create(['title' => 'PlaylistB']);
    $playlist->tracks()->sync($this->track1);

    $response = $this->actingAs($strangeUser)
                     ->json('POST', "removetracks/{$playlist->id}", [
                        'track' => $this->track1->first()->id,
                     ]);

    $response->assertStatus(401);
    $this->assertDatabaseHas('playlist_track', ['track_id' => $this->track1->first()->id]);
    $this->assertEquals(3, $playlist->tracks()->count());
  }

  /** @test */
  function all_album_tracks_can_be_added_to_a_playlist_at_once()
  {
    $user = factory(User::class)->create();
    $playlist = factory(Playlist::class)->create(['title' => 'PlaylistA','user_id' => $user->id]);

    $response = $this->actingAs($user)
                     ->json('POST', "addtracks/{$playlist->id}", [
                       'album' => $this->album->id,
                     ]);

    $response->assertStatus(201);
    $playlist = $user->playlists()->where('title', 'PlaylistA')->first();
    $this->assertNotNull($playlist);
    $this->assertEquals(5, $playlist->tracks->count());
  }

  /** @test */
  function user_can_delete_playlist()
  {
    $user = factory(User::class)->create();
    $playlist = factory(Playlist::class)->create(['title' => 'PlaylistA','user_id' => $user->id]);
    $response = $this->actingAs($user)
                     ->json('POST', "addtracks/{$playlist->id}", [
                       'album' => $this->album->id,
                     ]);
    $this->assertEquals(5, $playlist->tracks->count());

    $response = $this->actingAs($user)
                     ->json('POST', "playlists/{$playlist->id}");

    $response->assertStatus(302);
    $playlist = $user->playlists()->where('title', 'PlaylistA')->first();
    $this->assertNull($playlist);
  }
}
