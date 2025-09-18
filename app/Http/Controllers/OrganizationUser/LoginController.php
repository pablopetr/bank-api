<?php

namespace App\Http\Controllers\OrganizationUser;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationUser\LoginOrganizationUserRequest;
use App\Models\OrganizationUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __invoke(LoginOrganizationUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = OrganizationUser::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], Response::HTTP_UNAUTHORIZED);
        }

        if ($user->status !== UserStatus::Approved) {
            return response()->json(['message' => 'User is not active'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('org-auth', ['org'])->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
