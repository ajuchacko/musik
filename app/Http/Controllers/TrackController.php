<?php

namespace App\Http\Controllers;

use App\Track;
use App\Album;
use Illuminate\Http\Request;
use LaravelMP3;

class TrackController extends Controller
{
    public function __construct()
    {
      return $this->middleware(['auth', 'is_admin'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $albums = Album::all();
        return view('tracks.create',['albums' => $albums]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $album = Album::findOrFail($request->album);
        $file = $request->file('tracks');

          if($file) {
            $duration = LaravelMP3::getDuration($file);

            $destinationPath = 'tracks/';
            $name = date('Y_m_d_H_i').$file->getClientOriginalName();
            $file->move($destinationPath, $name);
          }


        $track = new Track;
        $track->title = $request->title;
        $track->duration = $duration;
        $track->filename = date('Y_m_d_H_i'). $file->getClientOriginalName();

        $album->tracks()->save($track) ?
                    $request->session()->flash('status', 'Track was added successfully!')
                  : $request->session()->flash('status', 'Adding Track failed!');

        return redirect()->route('albums.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function show(Track $track)
    {
      return response()->download(public_path(). '/tracks/'. $track->filename);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function edit(Track $track)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Track $track)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function destroy(Track $track)
    {

          if($track->filename) {
            $path = public_path().'/tracks/'. $track->filename;
            unlink($path);
          }

        $track->delete();
        return back();
    }
}
