@extends('templates.adminpanel')
@section('content')
    <div class="col-lg-8">
    <h3>Aggiungi utente</h3>
    @include('components.errors_input')

    <form action="{{route('users.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Nome</label>
            <input required type="text" name="name" id="name" value="{{old('name')? old('name') : ''}}" class="form-control" placeholder="Nome utente" aria-describedby="helpId">

        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input required type="email" name="email" id="email" value="{{old('email') ? old('email') : ''}}" class="form-control" placeholder="email" aria-describedby="helpId">

        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input required type="password" name="password" id="password" class="form-control" placpassword aria-describedby="helpId">

        </div>

        <div class="form-group">
            <label for="role">Ruolo</label>
            <select required name="role" id="role">
                <option value="">Seleziona</option>
                <option value="user">Utente</option>
                <option value="admin">Amministratore</option>
            </select>
        </div>

        <div class="form-group">

            <button name="" id="salva" type="submit" class="btn btn-primary" placeholder="" aria-describedby="helpId">Salva</button>
            <button name="" id="reset"  title="Riporta il form ai valori di partenza" type="reset" class="btn btn-warning" placeholder="" aria-describedby="helpId">Cancella</button>

        </div>

    </form>
    </div>
@endsection