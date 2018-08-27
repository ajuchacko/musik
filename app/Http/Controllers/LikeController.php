<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Track;
use App\Album;
use Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $tracks = Track::whereLikedBy(Auth::user()->id) // find only articles where user liked them
               ->with('likeCounter') // highly suggested to allow eager load
               ->get();

      return view('playlists.show',['tracks' => $tracks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $track = Track::findOrFail($request->track);
      $track->like(Auth::user()->id);
      $album = $track->album;

      $tracks = $album->tracks;
         return view('albums.show',['album' => $album, 'tracks' => $tracks]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $track = Track::findOrFail($request->track);
      $track->unlike(Auth::user()->id);
      $album = $track->album;

      $tracks = $album->tracks;
         return view('albums.show',['album' => $album, 'tracks' => $tracks]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
