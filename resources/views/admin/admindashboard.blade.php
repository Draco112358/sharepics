@extends('templates.adminpanel')
@section('content')
    <div class="row">
    <div class="col-lg-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">Utenti</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item">Utenti attivi: {{$dati['utentiAttivi']}}</li>
                <li class="list-group-item">
                    @if($dati['utentiDisattivati'] > 0)
                        <a href="{{route('users.index')}}?trashed=1">Utenti disattivati</a>: {{$dati['utentiDisattivati']}}</li>
                    @else
                        Utenti disattivati: {{$dati['utentiDisattivati']}}</li>
                    @endif
            </ul>
        </div>
    </div>
        <div class="col-lg-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">Albums e foto</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item">Albums totali condivisi: {{$dati['albumsTotali']}}</li>
                <li class="list-group-item">Numero categorie: {{$dati['categorie']}}</li>

            </ul>
        </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">Foto e commenti</h3>
            </div>
            <ul class="list-group">
                <li class="list-group-item">Foto condivise: {{$dati['fotoTotali']}}</li>
                <li class="list-group-item">
                    <a href="{{route('comments.list')}}">Commenti totali inseriti</a>: {{$dati['commenti']}}</li>

            </ul>
        </div>
    </div>
@stop