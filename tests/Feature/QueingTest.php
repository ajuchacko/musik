<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Que;
use App\Track;
use App\Album;

class QueingTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function a_track_can_be_qued()
  {
    // Arrange
    $que = Que::make();
    $album = factory(Album::class)->create();
    $track = Collect([
        factory(Track::class)->create(['title' => 'single track', 'album_id' => $album->id, 'duration' => 291]),
    ]);

    // Act
    $response = $this->json('POST', "tracks/que", ['tracks' => $track]);

    // Assert
    $response->assertStatus(201);
    $response->assertSessionHas('tracks');
    $response->assertExactJson([
      'que' => [
        'single track',
      ]
    ]);
    $response->assertJsonCount(1, 'que');
      // $key = array_search($track->first()->title, session('tracks.que'));
      // $this->assertCount(1, session('tracks.que'));
      // $this->assertEquals('single track', session("tracks.que.$key"));
      // dd(session('tracks'));
  }

  /** @test */
  function tracks_can_be_qued()
  {
    // Arrange
    $que = Que::make();
    $album = factory(Album::class)->create();
    $tracks = Collect([
        factory(Track::class)->create(['album_id' => $album->id, 'duration' => 291]),
        factory(Track::class)->create(['title' => 'second track', 'album_id' => $album->id, 'duration' => 123]),
        factory(Track::class)->create(['title' => 'third track', 'album_id' => $album->id, 'duration' => 100]),
    ]);

    // Act
    $response = $this->json('POST', "tracks/que", ['tracks' => $tracks]);

    // Assert
    $response->assertStatus(201);
    $response->assertSessionHas('tracks');
    $response->assertExactJson([
      'que' => [
        'Aararo',
        'second track',
        'third track'
      ]
    ]);
    $response->assertJsonCount(3, 'que');
      // $key = array_search($tracks->first()->title, session('tracks.que'));
      // $this->assertCount(3, session('tracks.que'));
      // $this->assertEquals('Aararo', session("tracks.que.$key"));
      // dd(session('tracks'));
  }

    /** @test */
   function albums_can_be_qued()
   {
     // Arrange
     $album = factory(Album::class)->create();
     $tracks = Collect([
         factory(Track::class)->create(['title' => 'track 1', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 2', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 3', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 4', 'album_id' => $album->id]),
     ]);

     // Act
     $response = $this->json('POST', "albums/que", ['album' => $album]);

     // Assert
     $response->assertStatus(201);
     $response->assertSessionHas('tracks');
     $response->assertExactJson([
       'que' => [
         'track 1',
         'track 2',
         'track 3',
         'track 4',
       ]
     ]);
     $response->assertJsonCount(4, 'que');
     // dd(session()->all());
     // $track = $tracks->first();
     // $key = array_search($track->title, session('tracks.que'));
     // $this->assertCount(4, session('tracks.que'));
     // $this->assertEquals('track 1', session("tracks.que.$key"));
   }

   /** @test */
   function random_tracks_and_albums_can_be_qued()
   {
     $this->withoutExceptionHandling( );
     // Arrange
     $album = factory(Album::class)->create();
     $tracks = Collect([
         factory(Track::class)->create(['album_id' => $album->id, 'duration' => 291]),
         factory(Track::class)->create(['title' => 'second track', 'album_id' => $album->id, 'duration' => 123]),
     ]);

     $response = $this->json('POST', "tracks/que", ['tracks' => $tracks]);

     $response->assertStatus(201);
     $response->assertJsonCount(2, 'que');

     $album = factory(Album::class)->create(['title' => 'Album Title']);
     $tracks = Collect([
         factory(Track::class)->create(['title' => 'track one', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track two', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 3',  'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 4', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 5',  'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 6', 'album_id' => $album->id]),
     ]);

     // Act
     $response = $this->json('POST', "albums/que", ['album' => $album]);

     // Assert
     $response->assertStatus(201);
     $response->assertSessionHas('tracks.que');
     $response->assertExactJson([
       'que' => [
         'Aararo',
         'second track',
         'track one',
         'track two',
         'track 3',
         'track 4',
         'track 5',
         'track 6',
       ]
     ]);
     $response->assertJsonCount(8, 'que');
     // $this->assertCount(8, session('tracks.que'));
     // $this->assertEquals('track one', session("tracks.que.2"));
     // dd(session()->all());
   }

   /** @test */ #album + track of that album = exception
   function already_qued_an_album_same_album_track_cannot_be_qued_again()
   {
     // Arrange
     $album = factory(Album::class)->create(['title' => 'First Album']);
     $tracks = Collect([
         factory(Track::class)->create(['title' => 'track one', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track two', 'album_id' => $album->id]),
     ]);
     $response = $this->json('POST', "albums/que", ['album' => $album]);
     $response->assertStatus(201);
     $response->assertSessionHas('tracks.que');
     $response->assertJsonCount(2, 'que');

     $album = factory(Album::class)->create(['title' => 'second Album']);
     $tracks = Collect([
         factory(Track::class)->create(['title' => 'track one', 'album_id' => $album->id]),
         // factory(Track::class)->create(['title' => 'track three', 'album_id' => $album->id]),
     ]);

     // Act
     // $this->withoutExceptionHandling();
     $response = $this->json('POST', "tracks/que", ['tracks' => $tracks]);

     // Assert
     $response->assertStatus(422);
     $response->assertSessionHas('tracks.que');
     $response->assertExactJson([
       'que' => [
         'track one',
         'track two',
         // 'track three',
       ]
     ]);
     $response->assertJsonCount(2, 'que');
   }

   /** @test */ #track + same track = exception
   function cannot_add_track_already_in_the_que()
   {
     // Arrange
     $album = factory(Album::class)->create(['title' => 'First Album']);
     $tracks = Collect([
         factory(Track::class)->create(['title' => 'same track', 'album_id' => $album->id]),
     ]);
     $response = $this->json('POST', "tracks/que", ['tracks' => $tracks]);
     $response->assertStatus(201);
     $response->assertSessionHas('tracks.que');
     $response->assertJsonCount(1, 'que');

     $tracks = Collect([
         factory(Track::class)->create(['title' => 'same track', 'album_id' => $album->id]),
     ]);

     // Act
     // $this->withoutExceptionHandling();
     $response = $this->json('POST', "tracks/que", ['tracks' => $tracks]);
     // Assert
     $response->assertStatus(422);
     $response->assertSessionHas('tracks.que');
     $response->assertExactJson([
       'que' => [
         'same track',
       ]
     ]);
     $response->assertJsonCount(1, 'que');
   }

   /** @test */ #track + album contain same track = all album tracks
   function adding_an_album_ques_all_tracks_without_repeating_same_album_tracks_already_qued()
   {
     // Arrange
     $album = factory(Album::class)->create(['title' => 'First Album']);
     $tracks = Collect([
         factory(Track::class)->create(['title' => 'same album track', 'album_id' => $album->id]),
     ]);
     $response = $this->json('POST', "tracks/que", ['tracks' => $tracks]);
     $response->assertStatus(201);
     $response->assertSessionHas('tracks.que');
     $response->assertJsonCount(1, 'que');

     $tracks = Collect([
         factory(Track::class)->create(['title' => 'same album track', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'same album different track', 'album_id' => $album->id]),
     ]);

     // Act
     $this->withoutExceptionHandling();
     $response = $this->json('POST', "albums/que", ['album' => $album]);
     // Assert
     $response->assertStatus(201);
     $response->assertSessionHas('tracks.que');
     $response->assertExactJson([
       'que' => [
         'same album track',
         'same album different track',
       ]
     ]);
     $response->assertJsonCount(2, 'que');
   }

   /** @test */
   function can_remove_track_from_current_que()
   {
     $this->withoutExceptionHandling();
     $album = factory(Album::class)->create(['title' => 'Album Title']);
     $tracks = Collect([
         factory(Track::class)->create(['title' => 'track one remove', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track two', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 3',  'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 4', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 5',  'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 6', 'album_id' => $album->id]),
     ]);
     $response = $this->json('POST', "albums/que", ['album' => $album]);
     // dd(session()->all());
     $response->assertStatus(201);
     $response->assertSessionHas('tracks.que');
     $this->assertCount(6, session('tracks.que'));

     $track = $tracks->first();
     $this->assertTrue(

       $tracks->pluck('title')->contains($track->title));
     $response = $this->json('POST', "tracks/remove", ['track' => $track]);

     $response->assertOk();
     $response->assertSessionHas('tracks.que');
     $response->assertJsonMissing([
       'que' => [
         $track->title,
       ]
     ]);
     $response->assertJsonCount(5, 'que');
     $this->assertCount(5, session('tracks.que'));
   }

   /** @test */
   function can_reset_que()
   {
     // Arrange
     $album = factory(Album::class)->create();
     $tracks = Collect([
         factory(Track::class)->create(['title' => 'track 1', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 2', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 3', 'album_id' => $album->id]),
         factory(Track::class)->create(['title' => 'track 4', 'album_id' => $album->id]),
     ]);
     $response = $this->json('POST', "albums/que", ['album' => $album]);

     // Act
     $response = $this->json('POST', "reset/que");

     // Assert
     $response->assertStatus(202);
     $response->assertSessionMissing('tracks');
     $response->assertSessionMissing('tracks.que');
   }
}
