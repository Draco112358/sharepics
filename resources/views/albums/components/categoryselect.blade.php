<div class="form-group">
    <label for="categories">Categorie</label>
    <select name="categories[]" id="categories" class="form-control" multiple>
        @foreach($categories as $category)
            <option {{in_array($category->id, $cats_selected)? 'selected' : ''}} value="{{$category->id}}">{{$category->name}}</option>
        @endforeach
    </select>
</div>