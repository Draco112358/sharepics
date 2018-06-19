@extends('templates.adminpanel')
@section('content')
    <h1>Aggiorna album</h1>
    @include('components.errors_input')
    <form action="{{route('album.store', $album->id)}}?adminpanel=1" method="POST" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <div class="form-group">
            <label for="">Nome</label>
            <input required type="text" name="name" id="name" class="form-control" value="{{old('name', $album->album_name)}}" placeholder="Album name" aria-describedby="helpId">

        </div>
        @include('albums.components.uploadfile')
        @include('albums.components.categoryselect')
        <div class="form-group">
            <label for="">Descrizione</label>
            <textarea type="text" name="description" id="description" class="form-control" placeholder="Album description" aria-describedby="helpId">{{old('description', $album->description)}}</textarea>

        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{route('albums.list')}}" class="btn btn-success">Indietro</a>
        {{--<a href="#" class="btn btn-success">Immagini album</a>--}}
    </form>
@stop
