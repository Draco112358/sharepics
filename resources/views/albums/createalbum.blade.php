@extends('templates.layout')
@section('assets')
    <link rel="stylesheet" href="{{asset('css/immaginecard.css')}}">
    <link rel="stylesheet" href="{{asset('css/infoutente.css')}}">

@stop
@section('content')
    <div class="row">
        @include('components.infoutente')
        <div class="col-lg-6">
    <h1>Nuovo Album</h1>
    @include('components.errors_input')
    <form action="{{route('album.save')}}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}

        <div class="form-group">
            <label for="">Name</label>
            <input required type="text" name="name" id="name" class="form-control" value="{{old('name')}}" placeholder="Album name" aria-describedby="helpId">

        </div>
        @include('albums.components.uploadfile')
        <div class="form-group">
            <label for="">Description</label>
            <textarea type="text" name="description" id="description" class="form-control" placeholder="Album description" aria-describedby="helpId">{{old('description')}}</textarea>

        </div>
        @include('albums.components.categoryselect')

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
        </div>
    </div>
@stop
