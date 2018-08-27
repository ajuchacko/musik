<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
    // return view('welcome');;
// });

Route::redirect('/', 'albums');

Auth::routes();

Route::get('/admin', 'AdminController@admin')
    ->middleware('is_admin')
    ->name('admin');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('likes', 'LikeController@store')->name('likes.store');
Route::get('favorites', 'LikeController@index')->name('likes.index');
Route::get('unlikes', 'LikeController@update')->name('likes.update');

// Albums
Route::resource('albums', 'AlbumController');
// Route::get('albums', 'AlbumController@index')->name('albums.index');
// Route::get('albums/create', 'AlbumController@create')->name('albums.create');
// Route::get('albums/{album}', 'AlbumController@show')->name('albums.show');
// Route::post('albums', 'AlbumController@store')->name('albums.store');
// Route::get('albums/{album}/edit', 'AlbumController@edit')->name('albums.edit');
// Route::put('albums/{album}', 'AlbumController@update')->name('albums.update');
// Route::delete('albums/{album}', 'AlbumController@destroy')->name('albums.destroy');

// Tracks
Route::get('tracks/create', 'TrackController@create')->name('tracks.create');
Route::get('tracks/{track}', 'TrackController@show')->name('tracks.show');//Download a track
Route::post('tracks', 'TrackController@store')->name('tracks.store');
Route::delete('tracks/{track}', 'TrackController@destroy')->name('tracks.destroy');

// Queue
Route::post('tracks/que', 'TrackQueController@que');
Route::post('albums/que', 'AlbumQueController@que');
Route::post('tracks/remove', 'TrackQueController@remove')->name('que.remove');
Route::post('reset/que', 'QueController@destroy')->name('que.reset');
Route::get('que', 'QueController@index')->name('que.index');

// Playlists
Route::get('playlists', 'PlaylistController@index')->name('playlists.index');
Route::get('playlists/{playlist}', 'PlaylistController@show')->name('playlists.show');
Route::post('playlists', 'PlaylistController@store')->name('playlists.store');
Route::post('playlists/{playlist}', 'PlaylistController@destroy')->name('playlists.destroy');

// Playlist_Track
Route::post('addtracks/{playlist}', 'PlaylistTracksController@store')->name('addplaylisttracks.store');
Route::post('removetracks/{playlist}', 'PlaylistTracksController@destroy')->name('removeplaylisttracks.destroy');;
