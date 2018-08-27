<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Track;
use App\Album;
use App\Que;

class AlbumQueController extends Controller
{
  public function que(Request $request)
  {
    $que = new Que;

    if(is_string($request->album)) {
      $album = (Album::findOrFail($request->album));
      $que = $que->queTracks($album->tracks);
      $que = $que->currentQue();

      $request->session()->flash('status', 'Album Tracks successfully Added in the Queue👍🏻!');
      return back();
    }else {
    $album = Album::findOrFail($request->album['id']);
    $que = $que->queTracks($album->tracks);
    $que = $que->currentQue();
    return response()->json($que , 201);
    }

  }
}
