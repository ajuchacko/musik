<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Track;
use Carbon\Carbon;

class Album extends Model
{
    protected $guarded = [];
    protected $dates = ['released_on'];

    public function getFormattedReleasedOnAttribute($value)
    {
      return $this->released_on->format('F Y');
    }

    public function tracks()
    {
      return $this->hasMany(Track::class);
    }

    public function getDurationAttribute()
    {
      return $this->tracks->sum('duration');
    }

}
