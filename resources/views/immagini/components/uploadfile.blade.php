@if(!$photo->id)
<div class="form-group">
    <label for="">Thumbnail</label>
    <input type="file" name="img_path" id="img_path" class="form-control" value="" placeholder="Album name" aria-describedby="helpId">

</div>
    @else
    <input type="hidden" name="img_path" value="{{$photo->img_path}}">
    <input type="hidden" name="old_albumID" value="{{$photo->album_id}}">
@endif
@if($photo->img_thumbnail)
    <div class="form-group">
        <img width="300" src="{{asset($photo->pathumb)}}" title="{{$photo->name}}" alt="{{$photo->name}}">
    </div>
@endif