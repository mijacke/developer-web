<?php

namespace App\Http\Requests\Locality;

use Illuminate\Foundation\Http\FormRequest;

class BulkUpdateLocalitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'localities' => ['required', 'array'],
            'localities.*.id' => ['required', 'exists:localities,id'],
            'localities.*.sort_order' => ['nullable', 'integer'],
            'localities.*.status' => ['nullable', 'string', 'max:50'],
        ];
    }
}

