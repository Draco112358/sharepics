@extends('templates.adminpanel')
@section('content')
    @csrf
    @if($disattivati == null)
        <h3>Utenti</h3>
        @else
        <h3>Utenti disattivati</h3>
    @endif
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
            <th>Email</th>
            <th>Ruolo</th>
            <th>Data inserimento</th>
            <th>Data eliminazione</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->role}}</td>
                <td>{{$user->created_at->format('d/m/Y H:i')}}</td>
                <td>@if ($user->deleted_at) {{$user->deleted_at->format('d/m/Y H:i')}} @else {{$user->deleted_at}} @endif</td>
                <td>
                    <div class="row">
                        <div class="col-3">
                            <a href="{{route('users.edit', $user->id)}}" class="btn btn-success"><span class="fa fa-pencil" title="Modifica"></span></a>
                        </div>
                        <div class="col-3">
                            @if(!$user->deleted_at)
                                <a href="{{route('users.destroy', $user->id)}}" class="btn btn-secondary elimina"><span class="fa fa-user-times" title="Cancella"></span></a>
                            @else
                                <a href="{{route('users.restore', $user->id)}}" class="btn btn-primary"><span class="fa fa-user-plus" title="Ripristina"></span></a>

                            @endif
                        </div>
                        <div class="col-3">
                            <a href="{{route('users.destroy', $user->id)}}?hard=1" class="btn btn-danger elimina"><span class="fa fa-times" title="Elimina definitivamente"></span></a>
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

                var Users = $(this).attr('href');

                var tr = this.parentNode.parentNode.parentNode.parentNode;
                $.ajax(Users,
                    {

                        method: 'DELETE',
                        data : {
                            '_token' : '{{csrf_token()}}'
                        },
                        complete : function (resp) {
                            if (resp.responseText == 1){
                                if (Users.indexOf('hard=1')>-1) {
                                    tr.parentNode.removeChild(tr);
                                }
                                else{
                                    window.location.reload();
                                }

                            } else {
                                alert('Problem contacting server');
                            }
                        }
                    }
                )
            });
        });
    </script>
@endsection
