<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInPersonGameApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price_limit' => ['nullable', 'numeric', 'min:0'],
            'participant_names' => ['required', 'array', 'min:3'],
            'participant_names.*' => ['required', 'string', 'max:100', 'distinct'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('participants')) {
            $this->merge([
                'participant_names' => array_values(array_filter(array_map('trim', $this->participants))),
            ]);
        }
    }
}