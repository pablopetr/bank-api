<?php

namespace App\Http\Controllers\OrganizationUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationUser\RegisterOrganizationUserRequest;
use App\Models\OrganizationUser;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterOrganizationUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = OrganizationUser::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('organization_users')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
