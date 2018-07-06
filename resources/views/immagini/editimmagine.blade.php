@extends('templates.layout')
@section('assets')
    <link rel="stylesheet" href="{{asset('css/immaginecard.css')}}">
    <link rel="stylesheet" href="{{asset('css/infoutente.css')}}">

@stop
@section('content')
    <div class="row">
        @include('components.infoutente')
        <div class="col-lg-6">
    <h1>
        @if($photo->id)
        Modifica foto
            @else
            Aggiungi foto
        @endif
    </h1>
    @include('components.errors_input')
    @if($photo->id)
        <form action="{{route('photos.update', $photo->id)}}" method="POST" enctype="multipart/form-data">

        @method('patch')
    @else
        <form action="{{route('photos.store')}}" method="POST" enctype="multipart/form-data">

    @endif
        <div class="form-group">
            <label for="">Nome</label>
            <input required type="text" name="name" id="name" class="form-control" value="{{$photo->name}}" placeholder="Photo name" aria-describedby="helpId">

        </div>
        <div>
            <div class="form-group">
                <label for="">Album</label>
                <select required name="album_id" id="album_id">
                    <option value="">SELECT</option>
                    @foreach($albums as $element)
                        <option {{$element->id==$album->id? 'selected':''}} value="{{$element->id}}">{{$element->album_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

         @csrf
        @include('immagini.components.uploadfile')
        <div class="form-group">
            <label for="">Descrizione</label>
            <textarea required type="text" name="description" id="description" class="form-control" placeholder="Photo description" aria-describedby="helpId">{{$photo->description}}</textarea>

        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        {{--<a href="{{route('album.showimages', $album->id)}}" class="btn btn-success">Indietro</a>--}}
            <input type="button" class="btn btn-success" value="Indietro" onClick="javascript:history.back()" name="button">
    </form>
        </div>
</div>
@stop
