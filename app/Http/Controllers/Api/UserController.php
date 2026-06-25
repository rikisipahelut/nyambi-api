<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Review;
use App\Models\SecurityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function me(): JsonResponse
    {
        $user = auth('api')->user();

        return response()->json([
            'data' => [
                'id'         => $user->id,
                'nama'       => $user->nama,
                'email'      => $user->email,
                'telepon'    => $user->telepon,
                'is_worker'  => $user->is_worker,
                'avatar_url' => $user->avatar
                    ? Storage::disk('public')->url($user->avatar)
                    : null,
                'created_at' => $user->created_at,
            ],
        ]);
    }

    public function stats(): JsonResponse
    {
        $user = auth('api')->user();

        $incomingOrders = 0;
        if ($user->is_worker && $user->workerProfile) {
            $incomingOrders = Order::where('worker_id', $user->workerProfile->id)
                ->where('status', 'menunggu')
                ->count();
        }

        return response()->json([
            'data' => [
                'orders'               => Order::where('user_id', $user->id)->count(),
                'favorites'            => Favorite::where('user_id', $user->id)->count(),
                'reviews'              => Review::where('user_id', $user->id)->count(),
                'unread_notifications' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
                'incoming_orders'      => $incomingOrders,
            ],
        ]);
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

    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|max:2048|mimes:jpeg,png,webp',
        ]);

        $user = auth('api')->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return response()->json([
            'data'    => ['avatar_url' => Storage::disk('public')->url($path)],
            'message' => 'Foto profil berhasil diperbarui',
        ]);
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

        SecurityLog::create([
            'user_id'    => $user->id,
            'event'      => 'password_changed',
            'email'      => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

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
