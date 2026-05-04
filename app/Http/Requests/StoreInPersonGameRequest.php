<?php

namespace App\Http\Requests;

use App\Models\Setting;
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
        $loginRequiredAfter = (int) Setting::get('inperson_login_required_after', 5);

        // If guest and count > loginRequiredAfter, cap at loginRequiredAfter
        $maxParticipants = Auth::check() ? 9999 : $loginRequiredAfter;

        return [
            'name'           => ['required', 'string', 'max:255'],
            'price_limit'    => ['nullable', 'numeric', 'min:0'],
            'participants'   => ['required', 'array', 'min:3', "max:{$maxParticipants}"],
            'participants.*' => ['required', 'string', 'max:100', 'distinct'],
        ];
    }

    public function messages(): array
    {
        $loginRequiredAfter = (int) Setting::get('inperson_login_required_after', 5);

        return [
            'participants.min'        => 'You need at least 3 participants to play.',
            'participants.max'        => "Please log in to add more than {$loginRequiredAfter} participants.",
            'participants.*.distinct' => 'All participant names must be unique.',
        ];
    }
}
