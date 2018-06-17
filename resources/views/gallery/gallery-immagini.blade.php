@extends('templates.layout')
@section('assets')
    <style>.list-group-item{
            background-color: #390313;
        }
    </style>

@stop
@section('content')

    <div class="row">
        <div class="col-lg-3">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h4 class="panel-title">Info album</h4>
                </div>
                <div class="box box-shadow">
                    <ul class="list-group">

                        <li class="list-group-item categories-list-item">Nome dell'album: {{$album->album_name}}</li>
                        @auth
                            <li class="list-group-item categories-list-item"><h3>Proprietario</h3>
                                <div>Nome: {{$proprietario->name}}</div>
                                <div>email: {{$proprietario->email}}</div>
                            </li>
                        @endauth
                        <li class="list-group-item categories-list-item"><h3>Foto</h3>
                            <div>Totale: {{$numeroDiFoto}}</div>
                        </li>

                    </ul>
                </div>
            </div>
            @include('components.back_button')
        </div>
<div class="col-lg-9">
        @forelse($immagini as $immagine)
            @guest
                    <a href="{{asset($immagine->path)}}" data-lightbox="{{$album->album_name}}">

            @else
                    <a href="{{route('comments.index', $immagine->id)}}">

            @endguest
                <img class="img-fluid" width="300" alt="{{$immagine->name}}" src="{{asset($immagine->pathumb)}}" >
            </a>
        @empty
            <div class="dropdown-message">Nessuna foto trovata</div>
       @endforelse
        @auth
            <div aria-colspan="5">{{$immagini->links()}}</div>
        @endauth
    </div>
    </div>


@endsection

