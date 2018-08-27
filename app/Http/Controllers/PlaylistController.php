<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Album;
use App\Track;
use Auth;
use App\Playlist;

class PlaylistController extends Controller
{
  private $user;

    public function __construct()
    {
      $this->middleware('auth');
      $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $track = request('track') ? request('track') : null ;
      // $playlists = Playlist::all();
      $playlists = User::find(Auth::user()->id)->playlists;
      return view('Playlists.index', ['playlists' => $playlists, 'track' => $track]);
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
        $this->validate(request(), [
          'title' => ['required'],
        ]);

          $playlist = new Playlist;
          $playlist->title = $request->title;
          Auth::user()->playlists()->save($playlist);
          if($request->track) {
            $this->trackStore($playlist, $request->track);
            return redirect()->route('playlists.index');
          }
        return response([], 201);
    }

    public function trackStore($playlist, $track)
    {
      $track = Track::findOrFail($track);

      if (Auth::user()->can('store', $playlist)) {
        $playlist->addTracks($track);
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
      $playlist = Playlist::findOrFail($id);

      $tracks = $playlist->tracks;

        return view('playlists.show',['tracks' => $tracks, 'playlist' => $playlist]);
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
        $playlist->delete();
        return redirect()->action('PlaylistController@index');
      }

      return response([], 401);
    }
}
