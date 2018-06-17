@extends('templates.adminpanel')
@section('content')
    @csrf
    <h3>Commenti</h3>
    @if(session()->has('message'))
        @component('components.alert_info')
            {{session()->get('message')}}
        @endcomponent
    @endif
    <table class="table table-striped" id="dataTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Autore</th>
            <th>Id foto</th>
            <th>Nome foto</th>
            <th>Commento</th>
            <th>Data creazione</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($commenti as $commento)
            <tr>
                <td>{{$commento->id}}</td>
                <td>{{$commento->commented->name}}</td>
                <td>{{$commento->commentable->id}}</td>
                <td>{{$commento->commentable->name}}</td>
                <td>{{$commento->comment}}</td>
                <td>{{$commento->created_at->format('d/m/Y H:i')}}</td>
                <td>
                    <div class="row">

                        <div class="col-3">
                            <a href="{{route('comments.destroy', $commento->id)}}" class="btn btn-danger elimina"><span class="fa fa-times" title="Elimina"></span></a>
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

                var Comments = $(this).attr('href');

                var tr = this.parentNode.parentNode.parentNode.parentNode;
                $.ajax(Comments,
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
