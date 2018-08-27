<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\TrackAlreadyAddedException;

class Que extends Model
{
  public $tracks;
  private $tracksList;
  private $currentQue;
  private $album;

  public function __construct()
  {
    $this->tracks = collect();
  }

  public function queTracks($tracks)
  {
    if(!is_array($tracks)) {
      $this->setAlbum(true);
      $tracks = $tracks->toArray();
      $this->queTracksList($tracks);
      return $this;
    } else {
      $this->setAlbum(false);
      $this->queTracksList($tracks);
      return $this;
    }
  }

  private function queTracksList($tracks)
  {
    if (!session('tracks.que')) {
      $return = $this->columns($tracks);
      return $this->createQue($return);
    } else {
      return  $this->updateQue($tracks);
    }
  }

  private function createQue($return)
  {
    $this->queItems($return);
  }

  private function columns($array)
  {
    return array_column($array, 'title', 'id');
  }

  public function updateQue($tracks)
  {
    $return = $this->columns($tracks);
    $this->currentQue = $this->currentQue();
    $this->reset();
    if($this->isAlbum()) {
      $latest = array_unique(array_merge($this->currentQue['que'], $return));

      $this->queItems($latest);

    } else {
      $this->updateQueList($return);
      $this->appendOldTracks();
    }
  }

  private function appendOldTracks()
  {
    $savedQue = $this->currentQue();
    $this->reset();
    $latest = array_merge($this->currentQue['que'], $savedQue['que']);

    $this->queItems($latest);

  }

  private function duplicate($item)
  {
    return in_array($item, $this->currentQue['que']);
  }

  private function updateQueList($return)
  {
    foreach($return as $key => $item) {
      $this->updateQueItem($item);
    }
  }

  private function updateQueItem($item) {
    if(!$this->duplicate($item)) {
      session()->push("tracks.que", $item);
    } else {
      $this->restoreOldTracks();
      throw new TrackAlreadyAddedException;
      }
  }

  private function restoreOldTracks()
  {
    $this->queItems($this->currentQue['que']);
  }

  public function queItems($array)
  {
    foreach ($array as $item) {
      session()->push("tracks.que", $item);
    }
  }

  public function currentQue()
  {
    return session('tracks');
  }

  public function reset()
  {
    return session()->forget('tracks');
  }

  public function remove($track)
  {
    $key = array_search($track, session('tracks.que'));
    session()->forget("tracks.que.$key");
    return $this;
  }




// Getters and Setters //

  private function setTrackList($trackList)
  {
    return $this->tracksList = $trackList;
  }

  public function getTracksList()
  {
    return $this->tracksList;
  }

  public function setAlbum($value)
  {
    $this->album = $value;
  }

  public function isAlbum()
  {
    return (bool) $this->album;
  }

}
