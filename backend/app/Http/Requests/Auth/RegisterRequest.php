<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Meno je povinné.',
            'name.max' => 'Meno môže mať maximálne 255 znakov.',
            'email.required' => 'Email je povinný.',
            'email.email' => 'Zadajte platný email.',
            'email.unique' => 'Tento email je už registrovaný.',
            'password.required' => 'Heslo je povinné.',
            'password.min' => 'Heslo musí mať minimálne 8 znakov.',
            'password.confirmed' => 'Heslá sa nezhodujú.',
        ];
    }
}
