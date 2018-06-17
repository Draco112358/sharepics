<?php

namespace App\Http\Requests;

use App\Models\Album;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AlbumEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->isAdmin()){
            return true;
        }
        $album = Album::find($this->id);
        if (\Gate::denies('gestione-album', $album)){
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('albums', 'album_name')->ignore(Album::find($this->id)->id)],
            //'description' => 'required',
            'album_thumbnail' => 'image'
            //'user_id' => 'required'
        ];
    }

    public function messages()
    {
        return   [
            'name.required' => 'Il campo Nome deve essere compilato',
            'name.unique' => 'Hai già un album con questo nome',
            'album_thumbnail.image' => 'La thumbnail deve essere un\'immagine',
            //'description.required' => 'Inserisci una descrizione'
        ];
    }
}
