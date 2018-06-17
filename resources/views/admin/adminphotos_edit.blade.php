@extends('templates.adminpanel')
@section('content')

    <h1>Modifica foto</h1>
    @include('components.errors_input')

        <form action="{{route('photos.update', $photo->id)}}?adminpanel=1" method="POST" enctype="multipart/form-data">

            @method('PATCH')

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

@stop