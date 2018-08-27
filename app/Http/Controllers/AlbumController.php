<?php

namespace App\Http\Controllers;

use App\Album;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AlbumController extends Controller
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
        $albums = Album::all();
        return view('albums.index', ['albums' => $albums]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('albums.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
        'title' => 'required',
        'released_by' => 'required',
        'released_on' => 'required',
      ]);
      $album = new Album;
      $album->title = $request->title;
      $album->released_by = $request->released_by;
      // $album->released_on =  \DateTime::createFromFormat('d/m/Y', $request->released_on);
      $album->released_on = Carbon::createFromFormat('d/m/Y', $request->released_on)->toDateTimeString();

      $file = $request->file('image');
        if($file) {
          $album->image = date('Y_m_d_H_i'). $file->getClientOriginalName();

          $destinationPath = 'albums/';
          $name = date('Y_m_d_H_i').$file->getClientOriginalName();
          $file->move($destinationPath, $name);
        }

      $album->save() ?
                  $request->session()->flash('status', 'Album was added successfully!')
                : $request->session()->flash('status', 'Adding Album failed!');




      return redirect()->route('albums.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {   $tracks = $album->tracks;
        return view('albums.show',['album' => $album, 'tracks' => $tracks]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        return view('albums.edit',['album' => $album]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        $album->title = $request->title ? $request->title : $album->title;
        $album->released_by = $request->released_by ?  $request->released_by : $album->released_by;
        $album->released_on = $request->released_on ? Carbon::createFromFormat('d/m/Y', $request->released_on)->toDateTimeString(): $album->released_on;
        $file = $request->file('image');

        if($file) {
          if($album->image) {
            $path = public_path().'/albums/'. $album->image;
            unlink($path);
          }
          $album->image = date('Y_m_d_H_i').$file->getClientOriginalName();

          $destinationPath = 'albums/';
          $name = date('Y_m_d_H_i').$file->getClientOriginalName();
          $file->move($destinationPath, $name);
        }


        $album->save() ?
                    $request->session()->flash('status', 'Album was updated successfully!')
                  : $request->session()->flash('status', 'Updating Album failed!');

          return redirect()->route('albums.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        $collection = ($album->tracks->pluck('filename'));
        $multiplied = $collection->map(function ($filename, $key) {
          if($filename) {
            $path = public_path().'/tracks/'. $filename;
            return unlink($path);
          }
          });
          if($album->image) {
            $path = public_path().'/albums/'. $album->image;
            unlink($path);
          }
        $album->delete();
        return back();
    }
}
