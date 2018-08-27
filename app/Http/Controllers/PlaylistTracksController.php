<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Playlist;
use App\Track;
use App\Album;
use Auth;

class PlaylistTracksController extends Controller
{

    private $user;

    public function __construct()
    {
      $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
      * Add a track to playlist
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request, Playlist $playlist)
     {
       if(request('album')) {
         $album = Album::findOrFail(request('album'));
         $tracks = $album->tracks;

         if ($this->user->can('store', $playlist)) {
           $playlist->addTracks($tracks);
           return response([], 201);
         }
       }

       $track = Track::findOrFail(request('track'));

       if (Auth::user()->can('store', $playlist)) {
         if(!$playlist->tracks->contains($track)) {
           $playlist->tracks()->attach($track);
         }
           $request->session()->flash('status', 'Track was added successfully!');
          return  redirect()->route('playlists.index') ;
       }

       return response([], 401);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Playlist $playlist)
    {
      if(Auth::user()->can('destroy', $playlist)) {
        $playlist->tracks()->detach(request('track'));
        return response([], 204);
    }
    return response([], 401);
  }
}
