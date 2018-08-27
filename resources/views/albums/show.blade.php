@extends('layouts.app')

@section('content')

  <div class="container bg-white">
    @if (session('status'))
        <div class="alert alert-success mt-2">
            {{ session('status') }}
        </div>
    @endif

    @if (session('que'))
        <div class="alert alert-warning mt-2">
            {{ session('que') }}
        </div>
    @endif


    <div class="col-md-12">
        <div class="col-md-3 order-1 mb-4">
          <img class="card-img-top" src="{{asset('albums/'.$album->image)}}" alt="Card image cap">
        </div>
        <div class="col-md-9">
          <h2>{{ucwords($album->title)}}</h2>
          <p>Released By<span class="text-muted">{{' ' . $album->released_by}}</span></p>
          <p>Released On <small class="font-weight-light">{{$album->formatted_released_on}}</small></p>
          <form class="" action="{{ url("albums/que")}}" method="post">
            @csrf
            <input type="hidden" name="album" value="{{$album->id}}">
            <a class=""><input type="submit" class="btn btn-primary mb-4" value="Play All"></input></a>

          </form>
          <h4>Tracks</h4>


          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                @auth<th scope="col">Favorite</th>@endauth
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
                @auth
                <td>
                  @if($track->liked())
                    <a href="{{action("LikeController@update",['track' => $track])}}">Unlike</a>
                  @else
                  <a href="{{action("LikeController@store",['track' => $track])}}">Like</a>
                  @endif
                {{-- <td>💌</td> --}}
                </td>
                @endauth
                <td>
                  <audio id="player" controls>
                        <source src='{{asset("tracks/$track->filename")}}' type="audio/mp3">
                  </audio>
                </td>
                <td>{{$track->title}}</td>
                <td>{{$track->duration}}</td>
                <td>
                  <div class="dropdown">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                      @if(auth()->check() && Auth::user()->type === 'admin')
                      <form class="" action="{{route('tracks.destroy', ['id' => $track->id])}}" method="post">
                        @csrf
                        @method('DELETE')
                        <a class="dropdown-item"><input type="submit" class="btn btn-grey" value="Delete"></input></a>
                        {{-- <a class="dropdown-item" href="{{route('tracks.destroy', ['id' => $track->id])}}">Delete</a> --}}
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

                      <form class="" action="{{ route("playlists.index")}}" method="get">
                        @csrf
                        <input type="hidden" name="track" value="{{$track->id}}">
                        <a class="dropdown-item"><input type="submit" class="btn btn-grey" value="Add Playlist"></input></a>
                      </form>


                    </div>
                    </div>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>


        </div>
    </div>

  </div>
@endsection
