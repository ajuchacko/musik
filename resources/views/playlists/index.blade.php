@extends('layouts.app')

@section('content')
  <div class="container">

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">

  <div class="list-group">
    <a href="#" class="list-group-item list-group-item-action active">
      Select Your Playlist
    </a>
    @foreach($playlists as $playlist)
    @if($track)
      <form class="list-group-item list-group-item-action" action="{{route('addplaylisttracks.store', ['playlist' => $playlist->id])}}" method="post">
        @csrf
        <input type="hidden" name="track" value="{{$track}}">
          <input type="submit" class="btn btn-grey" value="{{$playlist->title}}">
      </form>
    @endif
    @if(!$track)
    <a href="{{route('playlists.show', ['playlist' => $playlist->id])}}" class="list-group-item list-group-item-action">{{$playlist->title}}</a>
    @endif
  @endforeach
  </div>

    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">


                <div class="card-body">
                  <form class="form-inline" action="{{route('playlists.store')}}" method="post">
                    @csrf
                    <input type="hidden" name="track" value="{{$track}}">
                  <div class="form-group mb-2">
                    <label for="staticEmail2" class="sr-only">Create Playlist</label>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="Create Playlist">
                  </div>
                  <div class="form-group mx-sm-3 mb-2">
                    <label for="inputPassword2" class="sr-only">Create Playlist</label>
                    <input type="text" name="title" class="form-control" id="inputPassword2" placeholder="Create Playlist">
                  </div>
                  <button type="submit" class="btn btn-primary mb-2">Submit</button>
                  </form>
                </div>
            </div>
        </div>
    </div>

    
    </div>

@endsection
