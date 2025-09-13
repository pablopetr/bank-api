<?php

namespace App\Http\Requests\OrganizationUser;

use Illuminate\Foundation\Http\FormRequest;

class RegisterOrganizationUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:organization_users'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ];
    }
}
