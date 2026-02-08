<?php

namespace App\Http\Requests\Locality;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocalityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dm_id' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'status_label' => ['nullable', 'string', 'max:100'],
            'status_color' => ['nullable', 'string', 'max:20'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'rent' => ['nullable', 'numeric', 'min:0'],
            'floor' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'string'],
            'svg_path' => ['nullable', 'string'],
            'regions' => ['nullable', 'array'],
            'metadata' => ['nullable', 'array'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }
}
