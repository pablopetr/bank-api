<?php

namespace App\Http\Requests\IndividualUser;

use Illuminate\Foundation\Http\FormRequest;

class LoginIndividualUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }
}
