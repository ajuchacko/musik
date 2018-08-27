@extends('layouts.app')

@section('content')
  <div class="container">

    @if(isset($playlist))
    <form class="" action="{{route('playlists.destroy', ['playlist' => $playlist->id])}}" method="post">
      @csrf
      {{-- @method('DELETE') --}}
      <a class="dropdown-item"><input type="submit" class="btn btn-primary my-2" value="Delete Playlist"></input></a>
    </form>
    @endif

  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        @if(!isset($playlist))<th scope="col">Like</th>@endif
        <th scope="col">Listen</th>
        <th scope="col">Title</th>
        <th scope="col">Duration</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($tracks as $track)
      <tr>
        <th scope="row">{{$loop->iteration}}</th>
        @if(!isset($playlist))<td>💌</td>@endif
        <td>
          <audio id="player" controls>
                <source src='{{asset("tracks/$track->filename")}}' type="audio/mp3">
          </audio>
        </td>
        <td><a href="{{route('albums.show', ['album' => $track->album])}}">{{$track->title}}</a></td>
        <td>{{$track->duration}}</td>
        <td>
          <div class="dropdown">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
              @if(isset($playlist))
              <form class="" action="{{route('removeplaylisttracks.destroy', ['playlist' => $playlist->id])}}" method="post">
                @csrf
                <input type="hidden" name="track" value="{{$track->id}}">
                <a class="dropdown-item"><input type="submit" class="btn btn-grey" value="Remove"></input></a>
              </form>
              @endif

              <a class="dropdown-item" targer="_blank" href="{{route('tracks.show', [$track->id])}}">
                <button class="btn btn-grey">Download</button>
              </a>


              <form class="" action="{{ url("/tracks/que")}}" method="post">
                @csrf
                <input type="hidden" name="tracks" value="{{$track->id}}">
                <a class="dropdown-item"><input type="submit" class="btn btn-grey" value="Add to que"></input></a>
              </form>



            </div>
            </div>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection
