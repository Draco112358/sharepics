@extends('templates.adminpanel')
@section('content')
    @csrf
    <h3>Albums</h3>
    @if(session()->has('message'))
        @component('components.alert_info')
            {{session()->get('message')}}
        @endcomponent
    @endif
    <table class="table table-striped" id="dataTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Thumbnail</th>
            <th>Descrizione</th>
            <th>Proprietario</th>
            <th>N. di foto</th>
            <th>Data creazione</th>
            <th>Ultima modifica</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($albums as $album)
            <tr>
                <td>{{$album->id}}</td>
                <td>{{$album->album_name}}</td>
                <td>
                    <a href="{{route('gallery.albumphotos', $album->id)}}">
                        <img width="120" src="{{asset($album->path)}}" title="{{$album->album_thumbnail}}" alt="{{$album->album_name}}">
                    </a>
                </td>
                <td>{{$album->description}}</td>
                <td>{{$album->user->name}}</td>
                <td>{{$album->photos_count}}</td>
                <td>{{$album->created_at->format('d/m/Y H:i')}}</td>
                <td>{{$album->updated_at->format('d/m/Y H:i')}}</td>
                <td>
                    <div class="row">
                        <div class="col-3">
                            <a href="{{route('albums.modify', $album->id)}}?adminpanel=1" class="btn btn-success"><span class="fa fa-pencil" title="Modifica"></span></a>
                        </div>
                        <div class="col-3">
                            <a href="{{route('album.delete', $album->id)}}" class="btn btn-danger elimina"><span class="fa fa-times" title="Elimina"></span></a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@section('footer')
    @parent
    <script>
        $('document').ready(function () {

            $('div.alert').fadeOut(4000);
            $('#dataTable').on('click', 'a.elimina', function (ele) {
                ele.preventDefault();

                var Albums = $(this).attr('href');

                var tr = this.parentNode.parentNode.parentNode.parentNode;
                $.ajax(Albums,
                    {

                        method: 'DELETE',
                        data : {
                            '_token' : '{{csrf_token()}}'
                        },
                        complete : function (resp) {
                            if (resp.responseText == 1) {
                                tr.parentNode.removeChild(tr);
                            }

                            else {
                                alert('Problem contacting server');
                            }
                        }
                    }
                )
            });
        });
    </script>
@endsection
