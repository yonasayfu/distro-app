<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginApiRequest;
use App\Http\Resources\Api\V1\AuthUserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthTokenController extends Controller
{
    /**
     * Issue a personal access token for API clients.
     */
    public function store(LoginApiRequest $request): JsonResponse
    {
        $user = $request->authenticate();

        $token = $user->createToken($request->validated('device_name'))->plainTextToken;

        return response()->json([
            'message' => 'Authenticated successfully.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new AuthUserResource($user),
        ]);
    }

    /**
     * Revoke the current personal access token.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
