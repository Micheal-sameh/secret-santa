<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|exists:sessions,code',
            'name' => 'required|string|max:255',
        ];
    }
}
