<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'token.required' => 'Token je povinný.',
            'email.required' => 'Email je povinný.',
            'email.email' => 'Zadajte platný email.',
            'password.required' => 'Heslo je povinné.',
            'password.min' => 'Heslo musí mať minimálne 8 znakov.',
            'password.confirmed' => 'Heslá sa nezhodujú.',
        ];
    }
}
