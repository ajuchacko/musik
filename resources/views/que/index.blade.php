@extends('layouts.app')

@section('content')

  <div class="container">
    @if($tracks->isNotEmpty())
      <form class="" action="{{route('que.reset')}}" method="post">
        @csrf
        <a class="my-2"><input type="submit" class="my-2 btn btn-primary" value="Clear"></input></a>
      </form>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
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
          {{-- <td>💌</td> --}}
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

                <form class="" action="{{route('que.remove', ['title' => $track->title])}}" method="post">
                  @csrf
                  <a class="dropdown-item"><input type="submit" class="btn btn-grey" value="Remove"></input></a>
                  {{-- <a class="dropdown-item" href="{{route('tracks.destroy', ['id' => $track->id])}}">Delete</a> --}}
                </form>



                <a class="dropdown-item" targer="_blank" href="{{route('tracks.show', [$track->id])}}">
                  <button class="btn btn-grey">Download</button>
                </a>



              </div>
              </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @else
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">


                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p>Oops.... Your Queue is Empty</p>
                </div>
            </div>
        </div>
    </div>
  @endif
  </div>

@endsection
