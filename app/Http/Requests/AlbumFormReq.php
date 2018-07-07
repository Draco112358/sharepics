<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AlbumFormReq extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:albums,album_name',
            //'description' => 'required',
            'album_thumbnail' => 'required|image'
            //'user_id' => 'required'
        ];
    }

    public function messages()
    {
     return   [
            'name.unique' => 'Hai già un album con questo nome',
            'name.required' => 'Il campo Nome deve essere compilato',
            'album_thumbnail.required' => 'Seleziona una thumbnail',
            //'description.required' => 'Inserisci una descrizione'
        ];
    }
}
