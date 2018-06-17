<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required','string','email','max:255', Rule::unique('users')->ignore($this->user->id)],
            'role' => [
                'required',
                Rule::in(['user', 'admin'])]
        ];
    }

    public function messages()
    {
        return   [
            'name.required' => 'Inserisci un nome per l\'utente',
            'email.required' => 'Il campo email deve essere compilato',
            'email.unique' => 'Email già assegnata ad un altro utente',
            'role.required' => 'Seleziona un ruolo',

        ];
    }
}
