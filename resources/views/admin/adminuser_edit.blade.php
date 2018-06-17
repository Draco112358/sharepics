@extends('templates.adminpanel')
@section('content')
<div class="col-lg-8">
    <h3>Aggiorna utente</h3>
    @include('components.errors_input')

        <form action="{{route('users.update', $user->id)}}" method="POST" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
       <div class="form-group">
           <label for="name">Nome</label>
           <input required type="text" name="name" id="name" value="{{$user->name? $user->name : old('name')}}" class="form-control" placeholder="Nome utente" aria-describedby="helpId">

       </div>
       <div class="form-group">
           <label for="email">Email</label>
           <input required type="email" name="email" id="email" value="{{$user->email? $user->email : old('email')}}" class="form-control" placeholder="email" aria-describedby="helpId">

       </div>
       <div class="form-group">
           <label for="role">Ruolo</label>
           <select required name="role" id="role">
               <option value="">Seleziona</option>
               <option value="user" {{$user->role == 'user' ? 'selected' : ''}}>Utente</option>
               <option value="admin" {{$user->role == 'admin' ? 'selected' : ''}}>Amministratore</option>
           </select>
       </div>

       <div class="form-group">

           <button name="" id="aggiorna" type="submit" class="btn btn-primary" placeholder="" aria-describedby="helpId">Salva</button>
           <button name="" id="reset" type="reset" class="btn btn-warning" placeholder="" aria-describedby="helpId">Annulla</button>

       </div>

   </form>
</div>
@endsection