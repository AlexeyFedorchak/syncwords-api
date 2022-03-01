<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Get bearer token or fail with 401 error
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::whereEmail($request->email)
            ->first();

        if (!$user || !$user->email_verified_at) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout user
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->user()
            ->tokens()
            ->delete();

        return response()->json([
            'message' => 'Tokens Revoked'
        ]);
    }

    /**
     * refresh bearer token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        $personalToken = PersonalAccessToken::findToken(
            $request->bearerToken()
        );

        if (!$personalToken) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = User::find($personalToken->tokenable_id);

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user->tokens()->delete();

        return response()->json(
            ['token' => $user->createToken($user->name)->plainTextToken]
        );
    }
}
