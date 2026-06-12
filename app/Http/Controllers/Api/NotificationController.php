<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $userId        = auth('api')->id();
        $notifications = Notification::where('user_id', $userId)
            ->latest('created_at')
            ->paginate(20);

        return response()->json([
            'data' => $notifications->items(),
            'meta' => [
                'total'        => $notifications->total(),
                'page'         => $notifications->currentPage(),
                'limit'        => $notifications->perPage(),
                'total_pages'  => $notifications->lastPage(),
                'unread_count' => Notification::where('user_id', $userId)->where('is_read', false)->count(),
            ],
        ]);
    }

    public function markRead(string $id): JsonResponse
    {
        $notification = Notification::where('user_id', auth('api')->id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Notifikasi ditandai dibaca']);
    }

    public function markAllRead(): JsonResponse
    {
        Notification::where('user_id', auth('api')->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'Semua notifikasi ditandai dibaca']);
    }
}
