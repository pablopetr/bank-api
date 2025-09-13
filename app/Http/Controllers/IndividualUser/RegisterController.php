<?php

namespace App\Http\Controllers\IndividualUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndividualUser\RegisterIndividualUserRequest;
use App\Models\IndividualUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __invoke(RegisterIndividualUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = IndividualUser::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('ind-auth')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
