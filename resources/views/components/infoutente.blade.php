<div class="col-lg-4">
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h4 class="panel-title">Informazioni</h4>
        </div>
        <div class="box box-shadow">
            <ul class="list-group">
                <li class="list-group-item">
                    <h3>Utente</h3>
                    <div>Nome: {{$user->name}}</div>
                    <div>email: {{$user->email}}</div>
                </li>
                <li class="list-group-item">
                    <h3>Albums</h3>
                    <div>N. totale: {{$numeroAlbums}}</div>

                </li>
                @if($lastAlbum)
                <li class="list-group-item personalbackground">
                    <div>Ultimo album inserito da {{$user->name}}</div>

                    <p><div class="col-lg-12 no-padding lib-item" data-category="view">
                        <div class="lib-panel">
                            <div class="row box-shadow">
                                <div class="col-lg-6 col-md-4 col-sm-4">

                                    <a href="{{route('gallery.albumphotos', $lastAlbum->id)}}">
                                        <img class="img-fluid"  width="300" alt="{{$lastAlbum->album_name}}" src="{{asset($lastAlbum->path2)}}" >
                                    </a>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-8">
                                    <div class="lib-row lib-header">
                                        {{$lastAlbum->album_name}}
                                        <div class="lib-header-seperator"></div>
                                    </div>
                                    <div class="lib-row lib-desc">
                                        {{$lastAlbum->description}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </p>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>