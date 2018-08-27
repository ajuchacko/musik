<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Album;

class Track extends Model
{
    use \Conner\Likeable\LikeableTrait;


    public function album()
    {
      return $this->belongsTo(Album::class, 'album_id');
    }

    public function playlists()
    {
      return $this->belongsToMany('App\Playlist')->withTimestamps();
    }

    // public function getFormattedDurationAttribute()
    // {
    //   return number_format($this->duration / 60, 2);
    // }

    public function toArray()
    {
      return [
        'id' => $this->id,
        'title' => $this->title,
        'duration' => $this->duration,
        'album_id' => $this->album_id,
      ];
    }
}
