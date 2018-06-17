<div class="col-lg-12 no-padding lib-item" data-category="view">
        <div class="lib-panel">
            <div class="row box-shadow">
                <div class="col-lg-6 col-md-4 col-sm-4 fotobackground">
                    <a href="{{asset($immagine->path)}}" data-lightbox="{{$immagine->name}}">
                        <img class="img-fluid"  width="300" alt="{{$immagine->name}}" src="{{asset($immagine->pathumb)}}" >
                    </a>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-8">
                    <div class="lib-row lib-header">
                        {{$immagine->name}}
                        <div class="lib-header-seperator"></div>
                    </div>
                    <div class="lib-row lib-desc">
                        {{$immagine->description}}
                    </div>
                </div>
            </div>
        </div>
    </div>
