@extends('templates.layout')
@section('assets')
    <link rel="stylesheet" href="{{asset('css/immaginecard.css')}}">
    <link rel="stylesheet" href="{{asset('css/infoutente.css')}}">

@stop

@section('content')
<div class="row">
    @include('components.infoutente')
     <div class="col-lg-8">
    @if(session()->has('message'))
        @component('components.alert_info')
            {{session()->get('message')}}
        @endcomponent
    @endif
<table class="table table-striped">
    <thead class="thead-dark">
    <tr>

        <th class="text-center">Data creazione</th>
        <th class="text-center">Titolo</th>
        <th class="text-center">Album</th>
        <th class="text-left" colspan="2">Thumb</th>
    </tr>
    </thead>
    <tbody>
    @forelse($images as $image)
        <tr>

        <td>{{$image->created_at->format('d/m/Y H:i')}}</td>
        <td>{{$image->name}}</td>
            <td><a  href="{{route('albums')}}">{{$id->album_name}}</a></td>
        <td>
            <a href="{{route('comments.index', $image->id)}}">
            <img width="300" src="{{asset($image->pathumb)}}" title="{{$image->img_thumbnail}}" alt="{{$image->img_thumbnail}}">
            </a>
        </td>
            <td>
                <a href="{{route('photos.edit', $image->id)}}" class="btn btn-default">
                    <span title="Aggiorna foto" class="fa fa-pencil"></span>
                </a>

                <a href="{{route('photos.destroy', $image->id)}}" class="btn btn-danger">
                    <span title="Elimina foto" class="fa fa-minus"></span>
                </a>
            </td>
        </tr>
    @empty
            <tr><td colspan="5">
                    Immagini non trovate
                </td></tr>
        @endforelse
    <tr>
        <td colspan="5">
            {{$images->links()}}
        </td>
    </tr>
    </tbody>
</table>
     </div>
</div>
    @endsection
@section('footer')
    @parent
    <script>
        $('document').ready(function () {


            $('table').on('click', 'a.btn-danger', function (ele) {
                ele.preventDefault();
                var Immagine = $(this).attr('href');
                var tr = this.parentNode.parentNode;
                if (confirm('Sicuro di voler eliminare la foto?')) {
                    $.ajax(Immagine,
                        {

                            method: 'DELETE',
                            data: {
                                '_token': '{{csrf_token()}}'
                            },
                            complete: function (resp) {
                                console.log(resp);
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