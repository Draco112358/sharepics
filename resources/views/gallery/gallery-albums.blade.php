@extends('templates.layout')
@section('assets')
    <link rel="stylesheet" href="{{asset('css/immaginecard.css')}}">
    <style>.list-group-item{
            background-color: #390313;
        }
        .personalbackground{
            background-color: #040505;
        }
        .categories{
            color: black;
        }
        .categories-list-item{
            background-color: #110606;
        }

    </style>
@stop
@section('content')

        <div class="row">
    <div class="col-lg-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Condividi i tuoi momenti più importanti con SharePics</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item">Utenti iscritti: {{$numUtenti}}</li>
                <li class="list-group-item">Albums condivisi: {{$numAlbums}}</li>
                <li class="list-group-item personalbackground">
                    <div>Ultimo album inserito</div>

                    <div>
                        <div class="col-lg-12 no-padding lib-item" data-category="view">
                        <div class="lib-panel">
                            <div class="row box-shadow">
                                <div class="col-lg-6 col-md-4 col-sm-4">

                                        <a href="{{route('gallery.albumphotos', $lastAlbum->id)}}">
                                        <img class="img-fluid"  width="300" alt="{{$lastAlbum->album_name}}" src="{{asset($lastAlbum->path2)}}">
                                    </a>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <div class="lib-row lib-header">
                                        {{$lastAlbum->album_name}}
                                        <div class="lib-header-seperator"></div>
                                    </div>
                                    <div class="lib-row lib-desc">
                                        {{$lastAlbum->description}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right">Utente: {{$lastAlbum->user->name}}</div>
                </div>
                </li>

                @if(isset($immagine))
                <li class="list-group-item personalbackground">
                    <div>Foto più votata dagli utenti</div>

                    {{--<div style="text-align: right">Valutazione: {{$valutazione}} / 5</div>--}}
                    <div class="col-lg-12 no-padding lib-item" data-category="view">
                        <div class="lib-panel">
                            <div class="row box-shadow">
                                <div class="col-lg-6 col-md-4 col-sm-4">
                                    @auth
                                        <a href="{{route('comments.index', $immagine->id)}}">
                                            <img class="img-fluid" alt="{{$immagine->name}}" src="{{asset($immagine->pathumb)}}" >
                                        </a>
                                    @else
                                        <a href="{{asset($immagine->path)}}" data-lightbox="{{$immagine->name}}">
                                            <img class="img-fluid" alt="{{$immagine->name}}" src="{{asset($immagine->pathumb)}}" >
                                        </a>
                                    @endauth

                                        </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <div class="lib-row lib-header">
                                        {{$immagine->name}}
                                        <div class="lib-header-seperator"></div>
                                    </div>
                                    <div class="lib-row lib-desc">
                                        {{$immagine->description}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rating-block">

                        <h2 class="bold padding-bottom-7">{{$valutazione}} <small>/ 5</small></h2>
                        <button type="button" class="btn {{$valutazione < 0.5 ? 'btn-default btn-gray' : 'btn-warning'}} btn-sm" aria-label="Left Align">
                            <span class="fa fa-star" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn {{$valutazione < 1.5 ? 'btn-default btn-gray' : 'btn-warning'}} btn-sm" aria-label="Left Align">
                            <span class="fa fa-star" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn {{$valutazione < 2.5 ? 'btn-default btn-gray' : 'btn-warning'}} btn-sm" aria-label="Left Align">
                            <span class="fa fa-star" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn {{$valutazione < 3.5 ? 'btn-default btn-gray' : 'btn-warning'}} btn-sm" aria-label="Left Align">
                            <span class="fa fa-star" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn {{$valutazione < 4.5 ? 'btn-default btn-gray' : 'btn-warning'}} btn-sm" aria-label="Left Align">
                            <span class="fa fa-star" aria-hidden="true"></span>
                        </button>
                        <div style="text-align: right">Utente: {{$ownerPhoto}}</div>
                    </div>


                </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card-columns col-lg-6">
    @forelse($albums as $album)
        <div class="card">

            <a href="{{route('gallery.albumphotos', $album->id)}}">
                <img width="300" class="card-img-top" title="{{$album->album_name}}" src="{{asset($album->path2)}}" alt="{{$album->album_name}}">
            </a>

            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{route('gallery.albumphotos', $album->id)}}">{{$album->album_name}}</a>
                </h5>
            </div>
            <div class="card-text categories">
                    Categorie:

                    @foreach($album->categories as $category)
                        {{$category->name}}
                    @endforeach

            </div>
                <p class="card-text"><small class="text-muted">{{$album->created_at->format('d/m/Y')}}</small></p>

        </div>
        @empty
        Nessun album condiviso ancora.
    @endforelse
@auth
            <div aria-colspan="5">{{$albums->links()}}</div>
@endauth
    </div>

    <div class="col-lg-2">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h4 class="panel-title">Categorie</h4>
            </div>
            <div class="box box-shadow">
                @auth
                    <ul class="list-group">
                @forelse($cats as $category)
                    <li class="list-group-item categories-list-item">
                        <a href="{{route('gallery.category', $category->id)}}">{{$category->name}}</a>
                        <div style="text-align: right">({{$category->albums_count}})</div>
                    </li>
                @empty
                    Nessuna categoria presente.
                @endforelse
                    </ul>
                @else
                    Devi essere registrato per vedere le categorie.
                @endauth
            </div>
        </div>
    </div>

</div>


@endsection
