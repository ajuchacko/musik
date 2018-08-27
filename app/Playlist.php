<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $guarded = [];

    public function tracks()
    {
      return $this->belongsToMany('App\Track')->withTimestamps();
    }

    public function addTracks($tracks)
    {
      return $this->tracks()->sync($tracks);
    }
}
