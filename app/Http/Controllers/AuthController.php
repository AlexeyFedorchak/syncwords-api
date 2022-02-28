<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

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
        $user = User::where('email', $request['email'])
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
}
