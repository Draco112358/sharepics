@extends('templates.layout')
@section('assets')
    <link rel="stylesheet" href="{{asset('css/immaginecard.css')}}">
    <link rel="stylesheet" href="{{asset('css/infoutente.css')}}">

@stop
@section('content')
    <div class="row">
        @include('components.infoutente',
                    [
                        'user' => $user,
                        'numeroAlbums' => $numeroAlbums,
                        'lastAlbum' => $lastAlbum
                    ])
        <div class="col-lg-8">
    <h1>ALBUMS</h1>
    @if(session()->has('message'))
       @component('components.alert_info')
           {{session()->get('message')}}
       @endcomponent
    @endif
    <form>
    @csrf
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome album</th>
                <th>Thumbnail</th>
                <th>Proprietario</th>
                <th>Categorie</th>
                <th>Data creazione</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        @foreach($albums as $album)
            <tr>
                <td>({{$album->id}}) {{$album->album_name}}<br>{{$album->photos_count}} foto</td>
                    <td>
                    @if($album->album_thumbnail)
                        <a href="{{route('gallery.albumphotos', $album->id)}}">
                            <img width="120" src="{{asset($album->path)}}" title="{{$album->album_thumbnail}}" alt="{{$album->album_name}}">
                        </a>
                    @endif
                    </td>
                <td>{{$album->user->fullname}}</td>
                <td>
                    @if($album->categories)
                        <ul>
                        @foreach($album->categories as $category)
                            <li>{{$category->name}}</li>
                        @endforeach
                        </ul>
                    @else
                        Nessuna categoria
                    @endif
                </td>
                <td>{{$album->created_at->format('d/m/Y H:i')}}</td>
                <td>
                        <a title="Aggiungi foto" href="{{route('photos.create')}}?album_id={{$album->id}}" class="btn btn-default">
                            <span class="fa fa-plus"></span>
                        </a>

                    @if($album->photos_count)
                        <a href="{{route('album.showimages', $album->id)}}" class="btn btn-default">
                            <span title="Mostra foto" class="fa fa-eye"></span>
                        </a>
                    @endif
                    <a href="{{route('album.edit', $album->id)}}" class="btn btn-default">
                        <span title="Modifica album" class="fa fa-pencil"></span>
                    </a>
                    <a href="{{route('album.delete',$album->id)}}" class="btn btn-danger">
                        <span title="Elimina album" class="fa fa-minus"></span>
                    </a>
                </td>
            </tr>
        @endforeach
        <tr>
            <td class="row" colspan="5">
                <div class="col-md-8 push-2">{{$albums->links()}}</div>
            </td>
        </tr>
    </table>
    </form>
        </div>
    </div>
@endsection
@section('footer')
    @parent
    <script>
        $('document').ready(function () {

            $('div.alert').fadeOut(6000);
            $('table').on('click', 'a.btn-danger', function (ele) {
                ele.preventDefault();
                var Album = $(this).attr('href');
                var tr = this.parentNode.parentNode;
                if (confirm('Sicuro di voler eliminare l\'album?')) {
                    $.ajax(Album,
                        {

                            method: 'DELETE',
                            data: {
                                '_token': '{{csrf_token()}}'
                            },
                            complete: function (resp) {
                                if (resp.responseText == 1) {
                                    tr.parentNode.removeChild(tr);

                                } else {
                                    alert('Problem contacting server');
                                }
                            }
                        }
                    )
                }
            });
        });
    </script>
@endsection