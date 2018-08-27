<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Track;
use App\Album;
use App\Que;
use App\Exceptions\TrackAlreadyAddedException;

class TrackQueController extends Controller
{
  public function que(Request $request)
  {
    $que = new Que;
    try {
      if(is_string($request->tracks)) {
        $tracks = (Track::findOrFail($request->tracks));
        $que = $que->queTracks([$tracks->toArray()]);
        $que = $que->currentQue();
        $request->session()->flash('status', 'Track successfully Added in the Queue👍🏻!');
        return back();
      } else {
        $que = $que->queTracks($request->tracks);
        $que = $que->currentQue();
        return response()->json($que, 201);
      }

    } catch(TrackAlreadyAddedException $e) {
      $request->session()->flash('que', "Bummer!!! Track is already in the Que👍🏻!");
      $que = $que->currentQue();

        if(is_string($request->tracks)) {
        $album = Album::find($tracks->album_id);
        $tracks = $album->tracks;
        return back();
      } else {
        return response()->json($que, 422);
      }
    }
  }

  public function remove(Request $request)
  {
      if($request->title) {
        $que = new Que;
        $que = $que->remove($request->title);
        $que = $que->currentQue();
        return back();
      }

    $que = new Que;
    $que = $que->remove($request->track['title']);
    $que = $que->currentQue();
    return response()->json($que, 200);
   }

}
