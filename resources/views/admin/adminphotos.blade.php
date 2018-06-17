@extends('templates.adminpanel')
@section('content')
    @csrf
    <h3>Foto</h3>
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
            <th>Album</th>
            <th>Proprietario</th>
            <th>Data creazione</th>
            <th>Ultima modifica</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($photos as $photo)
            <tr>
                <td>{{$photo->id}}</td>
                <td>{{$photo->name}}</td>
                <td>
                    <a href="#">
                        <img width="120" src="{{asset($photo->pathumb)}}" title="{{$photo->img_thumbnail}}" alt="{{$photo->name}}">
                    </a>
                </td>
                <td>{{$photo->description}}</td>
                <td>{{$photo->album->album_name}}</td>
                <td>{{$photo->user->name}}</td>
                <td>{{$photo->created_at->format('d/m/Y H:i')}}</td>
                <td>{{$photo->updated_at->format('d/m/Y H:i')}}</td>
                <td>
                    <div class="row">
                        <div class="col-3">
                            <a href="{{route('adminphotos.edit', $photo->id)}}?adminpanel=1" class="btn btn-success"><span class="fa fa-pencil" title="Modifica"></span></a>
                        </div>
                        <div class="col-3">
                            <a href="{{route('photos.destroy', $photo->id)}}" class="btn btn-danger elimina"><span class="fa fa-times" title="Elimina"></span></a>
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

                var Photos = $(this).attr('href');

                var tr = this.parentNode.parentNode.parentNode.parentNode;
                $.ajax(Photos,
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
