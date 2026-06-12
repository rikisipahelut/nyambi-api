<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function me(): JsonResponse
    {
        return response()->json(['data' => auth('api')->user()]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = auth('api')->user();

        $validated = $request->validate([
            'nama'    => 'sometimes|string|max:100',
            'email'   => 'sometimes|email|max:150|unique:users,email,' . $user->id,
            'telepon' => 'sometimes|nullable|string|max:20',
        ]);

        $user->update($validated);

        return response()->json(['data' => $user, 'message' => 'Profil berhasil diperbarui']);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|different:current_password',
        ]);

        $user = auth('api')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'error' => ['code' => 'WRONG_PASSWORD', 'message' => 'Password lama tidak sesuai'],
            ], 422);
        }

        $user->update(['password' => $request->new_password]);

        return response()->json(['message' => 'Password berhasil diubah']);
    }

    public function destroy(): JsonResponse
    {
        $user = auth('api')->user();
        auth('api')->logout();
        $user->delete();

        return response()->json(null, 204);
    }
}
