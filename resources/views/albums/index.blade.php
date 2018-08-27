@extends('layouts.app')

@section('content')
  <div class="container">
    @if(auth()->check() && Auth::user()->type === 'admin')
    {{-- <a href="{{route('albums.create')}}"><button type="button" class="btn btn-primary mb-4">New Movie</button></a> --}}
    <a href="{{route('albums.create')}}"><button type="button" class="btn btn-primary">Add Album</button></a>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

   @endif
    <div class="row">
@foreach($albums as $movie)

      <div class="card mr-4 mt-4 col-xs-12 mx-auto" style="width: 18rem;">
      <a href="{{route('albums.show', ['id' => $movie->id])}}">  <img class="card-img-top" src="{{asset('albums/'.$movie->image)}}" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title">{{$movie->title}}</h5></a>

        </div>


        @if(auth()->check() && Auth::user()->type === 'admin')
        <div class="card-body mx-auto">
            <form class="" action="{{route('albums.edit', ['id' => $movie->id])}}" method="get">
              @csrf
              <input type="submit" class="btn btn-primary mt-2 ml-2" value="Edit"></input>
            </form>

          <form class="" action="{{route('albums.destroy', ['id' => $movie->id])}}" method="post">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-grey mt-2 ml-2" value="Delete"></input>
          </form>

        </div>
      @endif
      </div>

@endforeach
    </div>
{{-- {{session()->flush()}} --}}
    {{-- <div class="row mt-5">
      <div class="mx-auto"> {{ $movies->links() }}
      </div>
      </div> --}}
    </div>

@endsection
