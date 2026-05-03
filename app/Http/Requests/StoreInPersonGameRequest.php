<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreInPersonGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxParticipants = Auth::check() ? 100 : 5;

        return [
            'name'           => ['required', 'string', 'max:255'],
            'price_limit'    => ['nullable', 'numeric', 'min:0'],
            'participants'   => ['required', 'array', 'min:3', "max:{$maxParticipants}"],
            'participants.*' => ['required', 'string', 'max:100', 'distinct'],
        ];
    }

    public function messages(): array
    {
        return [
            'participants.min'   => 'You need at least 3 participants to play.',
            'participants.max'   => 'Guest users can only have up to 5 participants. Please log in to add more.',
            'participants.*.distinct' => 'All participant names must be unique.',
        ];
    }
}
