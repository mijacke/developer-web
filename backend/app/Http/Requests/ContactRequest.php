<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'firstName' => ['required', 'string', 'min:2', 'max:100'],
            'lastName' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^(\+421|0)[0-9]{9}$/'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'gdpr' => ['required', 'accepted'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'firstName.required' => 'Meno je povinné.',
            'firstName.min' => 'Meno musí mať aspoň 2 znaky.',
            'lastName.required' => 'Priezvisko je povinné.',
            'lastName.min' => 'Priezvisko musí mať aspoň 2 znaky.',
            'email.required' => 'E-mail je povinný.',
            'email.email' => 'Zadajte platný e-mail.',
            'phone.regex' => 'Telefónne číslo musí byť v formáte +421XXXXXXXXX alebo 09XXXXXXXX.',
            'message.required' => 'Správa je povinná.',
            'message.min' => 'Správa musí mať aspoň 10 znakov.',
            'gdpr.required' => 'Musíte súhlasiť so spracovaním osobných údajov.',
            'gdpr.accepted' => 'Musíte súhlasiť so spracovaním osobných údajov.',
        ];
    }
}
