<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'price_limit'  => ['nullable', 'numeric', 'min:0'],
            'end_date'     => ['required', 'date', 'after:today'],
            'meeting_date' => ['required', 'date', 'after_or_equal:end_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.after'              => 'The registration deadline must be a future date.',
            'meeting_date.after_or_equal' => 'The meeting date must be on or after the registration deadline.',
        ];
    }
}
