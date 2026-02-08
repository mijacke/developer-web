<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'dm_id' => ['sometimes', 'nullable', 'string', 'max:255'],
            'parent_id' => ['sometimes', 'nullable', 'integer', 'exists:projects,id'],
            'type' => ['sometimes', 'nullable', 'string', 'max:100'],
            'image' => ['nullable', 'string'],
            'map_key' => ['sometimes', 'nullable', 'string', 'max:255'],
            'sort_order' => ['sometimes', 'nullable', 'integer'],
            'regions' => ['sometimes', 'nullable', 'array'],
            'frontend' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
