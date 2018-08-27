@extends('layouts.app')

@section('content')
  <div class="container">

  <form class="col-md-10" action="{{route('albums.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">Title</label>
      <input type="text" name="title" class="form-control" id="inputEmail4" placeholder="">
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Released By</label>
      <input type="text" name="released_by" class="form-control" id="inputPassword4" placeholder="">
    </div>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputCity">Released On (23/07/2018)</label>
      <input type="text" name="released_on" class="form-control" id="inputCity">
    </div>

    <div class="input-group mb-3 col-md-10">
      <div class="input-group-prepend">
        <span class="input-group-text">Add Image</span>
      </div>
      <div class="custom-file mr-4">
        <input type="file" name="image" class="custom-file-input" id="inputGroupFile01">
        <label class="custom-file-label" for="inputGroupFile01">Choose Image</label>
      </div>

    </div>

  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
@endsection
