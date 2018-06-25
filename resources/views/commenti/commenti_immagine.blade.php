@extends('templates.layout')
@section('assets')
    <link rel="stylesheet" href="{{asset('css/commentlist.css')}}">
    <link rel="stylesheet" href="{{asset('css/immaginecard.css')}}">
    <link rel="stylesheet" href="{{asset('css/averagephoto.css')}}">
    <link rel="stylesheet" href="{{asset('css/ratings.css')}}">
    <style>.lib-panel .row,
        .lib-panel .col-md-6{
            background-color: #292323;
        }</style>

@stop
@section('content')


            <div class="row row-margin-bottom">
                <div class="col-lg-4">
            @include('commenti.components.immagine_card')

            <div class="rating-block">
                <h4>Valutazione</h4>
                <h2 class="bold padding-bottom-7">{{$mediaVoti}} <small>/ 5</small></h2>
                <button type="button" class="btn {{$mediaVoti < 0.5 ? 'btn-default btn-gray' : 'btn-warning'}} btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn {{$mediaVoti < 1.5 ? 'btn-default btn-gray' : 'btn-warning'}} btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn {{$mediaVoti < 2.5 ? 'btn-default btn-gray' : 'btn-warning'}} btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn {{$mediaVoti < 3.5 ? 'btn-default btn-gray' : 'btn-warning'}} btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn {{$mediaVoti < 4.5 ? 'btn-default btn-gray' : 'btn-warning'}} btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            </div>
                    @include('components.back_button')
            </div>



        <div class="col-lg-8">
            @include('components.errors_input')
            <div class="row">
            <h2>Commenti ({{$numeroCommenti}}) <div class="pull-right"><a href="#" id="addacomment" class="btn btn-primary">Commenta e vota</a> </div></h2>
            </div>
        <div class="row" id="addcomment" style="display: none;">

            <form method="POST" action="{{route('comments.store')}}">
                @csrf
                <input name="photoID" type="hidden" value="{{$immagine->id}}">
                <div class="table">
                    <div class="row">
                        <div class="col-lg-6 col-md-5">
                    <textarea required name="nuovoCommento" class="form-control" placeholder="Comment content..."></textarea><br/>
                        </div>
                        <div class="col-lg-6 col-md-7 rating">

                            <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Rocks!">5 stelle</label>
                            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Rocks!">4 stelle</label>
                            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Pretty good">3 stelle</label>
                            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Pretty good">2 stelle</label>
                            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Meh">1 stella</label>

                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Invia</button>
            </form>

        </div>
        <hr>
        @forelse($commenti as $commento)
        <div>
            <div class="row comment">
                <br class="head">
                <small><strong class='user'>{{$commento->commented->name}} </strong>{{$commento->created_at->format('d/m/Y H:i')}} </small>
            </div>
            <table>
                <tr>
                    <td>
                        <p>{{$commento->comment}}</p>
                    </td>
                    <td>
                        <a href="{{route('comments.destroy', $commento->id)}}" class="btn btn-dark" @if(Auth::user()->isAdmin()) style="display: block" @else style="display: none" @endif>
                            <span title="Elimina messaggio" class="fa fa-minus"></span>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
        @empty
            Nessun commento.
        @endforelse
    <hr>
    <div aria-colspan="5">{{$commenti->links()}}</div>
        </div>
    </div>


@stop

@section('footer')
@parent
    <script>
        $(document).on('click', '#addacomment', function(){
            $('#addcomment').toggle();
        });
        $('document').ready(function () {

            $('div.alert').fadeOut(6000);
            $('table').on('click', 'a.btn-dark', function (ele) {
                ele.preventDefault();
                var Message = $(this).attr('href');
                var tr = this.parentNode.parentNode.parentNode.parentNode.parentNode;
                if (confirm('Sicuro di voler eliminare il messaggio?')) {
                    $.ajax(Message,
                        {

                            method: 'DELETE',
                            data: {
                                '_token': '{{csrf_token()}}'
                            },
                            complete: function (resp) {
                                if (resp.responseText == 1) {
                                    tr.parentNode.removeChild(tr);

                                } else {
                                    alert('Problem contacting server');
                                }
                            }
                        }
                    )
                }
            });
        });
    </script>
@stop