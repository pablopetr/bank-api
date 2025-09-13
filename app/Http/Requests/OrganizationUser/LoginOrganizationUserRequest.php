<?php

namespace App\Http\Requests\OrganizationUser;

use Illuminate\Foundation\Http\FormRequest;

class LoginOrganizationUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }
}
