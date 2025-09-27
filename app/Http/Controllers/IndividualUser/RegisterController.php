<?php

namespace App\Http\Controllers\IndividualUser;

use App\Actions\IndividualUser\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndividualUser\RegisterIndividualUserRequest;
use App\Models\IndividualUser;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterIndividualUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = (new CreateUser())->execute($data);

        $token = $user->createToken('ind-auth')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
