<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameApiRequest extends FormRequest
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
            'end_date' => ['required', 'date', 'after:today'],
            'meeting_date' => ['required', 'date', 'after:end_date'],
        ];
    }
}