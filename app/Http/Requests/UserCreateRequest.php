<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isAdmin();
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => [
                'required',
                Rule::in(['user', 'admin'])]
        ];
    }

    public function messages()
    {
        return   [
            'name.required' => 'Il campo Nome non può essere vuoto',
            'email.required' => 'Il campo email deve essere compilato',
            'email.unique' => 'Email già registrata. Inseriscine una nuova',
            'password.required' => 'Inserisci una password',
            'password.min:6' => 'La password deve essere più lunga di 6 caratteri',
            'role.required' => 'Seleziona un ruolo',
        ];
    }
}
