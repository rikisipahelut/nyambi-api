<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email',
            'telepon'  => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $user  = User::create($validated);
        $token = auth('api')->login($user);

        return response()->json([
            'access_token' => $token,
            'expires_in'   => config('jwt.ttl') * 60,
            'user'         => $this->userPayload($user),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $token = auth('api')->attempt(['email' => $request->email, 'password' => $request->password]);

        if (!$token) {
            return response()->json([
                'error' => ['code' => 'INVALID_CREDENTIALS', 'message' => 'Email atau password salah'],
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'expires_in'   => config('jwt.ttl') * 60,
            'user'         => $this->userPayload(auth('api')->user()),
        ]);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return response()->json(['message' => 'Berhasil logout']);
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = auth('api')->refresh();
            return response()->json([
                'access_token' => $token,
                'expires_in'   => config('jwt.ttl') * 60,
            ]);
        } catch (JWTException) {
            return response()->json([
                'error' => ['code' => 'TOKEN_INVALID', 'message' => 'Tidak dapat memperbarui token'],
            ], 401);
        }
    }

    private function userPayload(User $user): array
    {
        return [
            'id'        => $user->id,
            'nama'      => $user->nama,
            'email'     => $user->email,
            'is_worker' => $user->is_worker,
        ];
    }
}
