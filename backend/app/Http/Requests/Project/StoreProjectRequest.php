<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'dm_id' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:projects,id'],
            'type' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'string'],
            'map_key' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'regions' => ['nullable', 'array'],
            'frontend' => ['nullable', 'array'],
        ];
    }
}
