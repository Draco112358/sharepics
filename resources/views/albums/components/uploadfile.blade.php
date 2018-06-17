<div class="form-group">
    <label for="">Thumbnail</label>
    <input type="file" name="album_thumbnail" id="album_thumbnail" class="form-control" value="{{$album->album_thumbnail2}}" placeholder="Album name" aria-describedby="helpId">

</div>
@if($album->album_thumbnail)
    <div class="form-group">
        <img width="300" src="{{asset($album->path2)}}" title="{{$album->album_thumbnail2}}" alt="{{$album->album_name}}">
    </div>
@endif