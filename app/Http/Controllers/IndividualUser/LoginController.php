<?php

namespace App\Http\Controllers\IndividualUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationUser\LoginOrganizationUserRequest;
use App\Models\IndividualUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(LoginOrganizationUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = IndividualUser::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = $user->createToken('ind-auth', ['ind'])->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
