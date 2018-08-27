<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\Album;

class AlbumListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function users_can_view_album_listing()
    {
      // arrange
      $album = Album::create([
        'title' => 'Koode',
        'released_by' => 'Muzik247',
        'released_on' => Carbon::parse('June 15 2018'),
      ]);

      // act
      $response = $this->get("albums/$album->id");

      //assert
      $response->assertOk();
      $response->assertViewIs('albums.show');
      $response->assertSee('Koode');
      $response->assertSee('Muzik247');
      $response->assertSee('June 2018');
    }

}
