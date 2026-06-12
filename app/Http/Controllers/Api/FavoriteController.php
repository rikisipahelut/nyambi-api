<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\WorkerProfile;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    public function index(): JsonResponse
    {
        $favorites = Favorite::where('user_id', auth('api')->id())
            ->with('worker.user')
            ->get();

        return response()->json([
            'data' => $favorites->map(fn($f) => [
                'worker_id'    => $f->worker->id,
                'nama'         => $f->worker->user?->nama,
                'specialty'    => $f->worker->specialty,
                'location'     => $f->worker->location,
                'rating'       => $f->worker->rating,
                'status'       => $f->worker->status,
                'image_url'    => $f->worker->image_url,
                'favorited_at' => $f->created_at,
            ]),
        ]);
    }

    public function store(string $workerId): JsonResponse
    {
        WorkerProfile::findOrFail($workerId);

        $userId = auth('api')->id();

        if (Favorite::where('user_id', $userId)->where('worker_id', $workerId)->exists()) {
            return response()->json(['error' => ['code' => 'ALREADY_FAVORITED', 'message' => 'Sudah ditambahkan ke favorit']], 409);
        }

        Favorite::create(['user_id' => $userId, 'worker_id' => $workerId]);

        return response()->json(['message' => 'Berhasil ditambahkan ke favorit'], 201);
    }

    public function destroy(string $workerId): JsonResponse
    {
        $deleted = Favorite::where('user_id', auth('api')->id())
            ->where('worker_id', $workerId)
            ->delete();

        if (!$deleted) {
            return response()->json(['error' => ['code' => 'NOT_FOUND', 'message' => 'Favorit tidak ditemukan']], 404);
        }

        return response()->json(null, 204);
    }
}
