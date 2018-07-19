@extends('templates.adminpanel')

@section('content')

    <h3>Categorie</h3>
    @if(session()->has('message'))
        @component('components.alert_info')
            {{session()->get('message')}}
        @endcomponent
    @endif
    @include('components.errors_input')
    <div class="row">
        <div class="col-lg-7">
    <table class="table table-striped" id="dataTable">
       <thead>
        <tr>
            <th>ID</th>
            <th>Nome categoria</th>
            <th>Data creazione</th>
            <th>Data modifica</th>
            <th>Data eliminazione</th>
            <th>N. di albums</th>
            <th>&nbsp;</th>
        </tr>
       </thead>
        <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{$category->id}}</td>
                <td>{{$category->name}}</td>
                <td>{{$category->created_at->format('d/m/Y H:i')}}</td>
                <td>{{$category->updated_at->format('d/m/Y H:i')}}</td>
                <td>@if ($category->deleted_at) {{$category->deleted_at->format('d/m/Y H:i')}} @else {{$category->deleted_at}} @endif</td>
                <td>{{$category->albums_count}}</td>
                <td>


                    <div class="col-3">
                        @if(!$category->deleted_at)
                            <a href="{{route('categories.destroy', $category->id)}}" class="btn btn-secondary elimina"><span class="fa fa-user-times" title="Cancella"></span></a>
                        @else
                            <a href="{{route('categories.restore', $category->id)}}" class="btn btn-primary"><span class="fa fa-user-plus" title="Ripristina"></span></a>

                        @endif
                    </div>
                    <div class="col-3">
                        <a href="{{route('categories.destroy', $category->id)}}?hard=1" class="btn btn-danger elimina"><span class="fa fa-times" title="Elimina definitivamente"></span></a>
                    </div>

                </td>
            </tr>
        @empty
            <tfoot>
                <tr>
                    <td colspan="5">
                        Nessuna categoria
                    </td>
                </tr>
            </tfoot>
        @endforelse
    </tbody>
    </table>
        </div>
    <div class="col-lg-5">
        <h3>Aggiungi categoria</h3>
        <form action="{{route('categories.store')}}" method="POST">
            @csrf
            <div class="form-group">
                <input required name="name" id="categoria" class="form-control" placeholder="Nome categoria">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Salva</button>
            </div>
        </form>
        <h3>Aggiorna categorie</h3>

            <div class="row">

                <form action="{{route('categories.update')}}" method="POST">
                    @method('PATCH')
                    @csrf
                    <div class="form-group">
                        <label for="category_name">Categorie</label>
                        <select required name="catID" id="category_name">
                            <option value="">Seleziona</option>
                            @foreach($categories as $cat)
                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                            @endforeach
                        </select>
                    </div>
                <div>
                <input required name="name" id="category_name" class="form-control" placeholder="Nome categoria">
                </div>


            <div class="form-group">
                <button type="submit" class="btn btn-success">Aggiorna</button>
            </div>

        </form>
    </div>
    </div>
    </div>

@endsection
@section('footer')
    @parent
    <script>
        $('document').ready(function () {

            $('div.alert').fadeOut(4000);
            $('#dataTable').on('click', 'a.elimina', function (ele) {
                ele.preventDefault();

                var Categories = $(this).attr('href');

                var tr = this.parentNode.parentNode.parentNode;
                $.ajax(Categories,
                    {

                        method: 'DELETE',
                        data : {
                            '_token' : '{{csrf_token()}}'
                        },
                        complete : function (resp) {
                            if (resp.responseText == 1){
                                if (Categories.indexOf('hard=1')>-1) {
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