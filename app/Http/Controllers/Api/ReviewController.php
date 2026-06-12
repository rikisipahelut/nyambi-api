<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|string|exists:orders,id',
            'rating'   => 'required|integer|between:1,5',
            'comment'  => 'nullable|string',
        ]);

        $user  = auth('api')->user();
        $order = Order::findOrFail($validated['order_id']);

        if ($order->user_id !== $user->id) {
            return response()->json(['error' => ['code' => 'FORBIDDEN', 'message' => 'Akses ditolak']], 403);
        }

        if ($order->status !== 'selesai') {
            return response()->json([
                'error' => ['code' => 'ORDER_NOT_COMPLETE', 'message' => 'Ulasan hanya bisa ditulis setelah pesanan selesai'],
            ], 422);
        }

        if (Review::where('order_id', $order->id)->exists()) {
            return response()->json([
                'error' => ['code' => 'ALREADY_REVIEWED', 'message' => 'Pesanan ini sudah diulas'],
            ], 409);
        }

        $review = Review::create([
            ...$validated,
            'user_id'   => $user->id,
            'worker_id' => $order->worker_id,
        ]);

        $this->recalcWorkerRating($order->worker_id);

        return response()->json(['data' => $review, 'message' => 'Ulasan berhasil ditambahkan'], 201);
    }

    public function me(): JsonResponse
    {
        $reviews = Review::where('user_id', auth('api')->id())
            ->with('worker.user')
            ->latest('created_at')
            ->paginate(10);

        return response()->json([
            'data' => $reviews->map(fn($r) => [
                'id'       => $r->id,
                'order_id' => $r->order_id,
                'worker'   => [
                    'id'        => $r->worker?->id,
                    'nama'      => $r->worker?->user?->nama,
                    'specialty' => $r->worker?->specialty,
                ],
                'rating'     => $r->rating,
                'comment'    => $r->comment,
                'created_at' => $r->created_at,
            ]),
        ]);
    }

    private function recalcWorkerRating(string $workerId): void
    {
        $avg = Review::where('worker_id', $workerId)->avg('rating') ?? 0;

        \App\Models\WorkerProfile::where('id', $workerId)
            ->update(['rating' => round((float) $avg, 2)]);
    }
}
