<?php

namespace App\Http\Controllers\OrganizationUser;

use App\Actions\OrganizationUser\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationUser\RegisterOrganizationUserRequest;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterOrganizationUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = (new CreateUser)->execute($data);

        $token = $user->createToken('organization_users')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
