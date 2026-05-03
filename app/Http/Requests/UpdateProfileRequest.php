<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['required', 'email', 'unique:users,email,' . Auth::id()],
            'current_password'     => ['nullable', 'string'],
            'password'             => ['nullable', 'string', 'min:8', 'confirmed', Password::defaults()],
            'password_confirmation'=> ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already taken by another account.',
        ];
    }
}
