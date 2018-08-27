<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Album;
use App\Track;
use Carbon\Carbon;

class AlbumTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function can_get_formatted_released_on_date()
  {
    $album = factory(Album::class)->make([
      'released_on' => Carbon::parse('June 15 2018'),
    ]);

    $message = "$this->r Album released on date is not formatted$this->n";
    $this->assertEquals('June 2018', $album->formatted_released_on, $message);
  }

  /** @test */
  public function can_calculate_total_duration()
  {
      $album = factory(Album::class)->create();

      $tracks = Collect([
          factory(Track::class)->make(['album_id' => $album->id, 'duration' => 291]),
          factory(Track::class)->make(['album_id' => $album->id, 'duration' => 123]),
          factory(Track::class)->make(['album_id' => $album->id, 'duration' => 100]),
      ]);

      // 3. Save them through the `tracks` relationship to
      //    set the foreign keys
      $album->tracks()->saveMany($tracks);

      $this->assertEquals(514, $album->duration);
  }
}
