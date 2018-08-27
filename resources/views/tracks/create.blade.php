@extends('layouts.app')

@section('content')
  <div class="container">
    <form class="col-md-10" action="{{route('tracks.store')}}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputEmail4"><h4>Select Album</h4></label>
          <div class="input-group">
          <select name="album" class="custom-select" id="inputGroupSelect04" aria-label="Example select with button addon">
            <option selected>Choose...</option>
            @foreach($albums as $album)
            <option value="{{$album->id}}">{{$album->title}}</option>
            @endforeach
          </select>
        </div>
        </div>
      <div class="input-group mb-3 col-md-7 mt-3">

      <div class="input-group-prepend">
        <span class="input-group-text">Add Tracks</span>
      </div>
      <div class="custom-file">
        <input type="file" name="tracks" class="custom-file-input" id="inputGroupFile01">
        <label class="custom-file-label" for="inputGroupFile01">Choose Tracks</label>
      </div>
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Track Title</label>
      <input type="text" name="title" class="form-control" id="inputPassword4" placeholder="">
    </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Add</button>
  </form>
  </div>
@endsection
