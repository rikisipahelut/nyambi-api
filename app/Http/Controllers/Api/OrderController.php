<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WorkerProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'worker_id'  => 'required|string|exists:worker_profiles,id',
            'tanggal'    => 'required|date|after_or_equal:today',
            'waktu'      => 'required|date_format:H:i',
            'deskripsi'  => 'nullable|string',
            'alamat'     => 'required|string',
            'telepon'    => 'required|string|max:20',
        ]);

        $order = Order::create([...$validated, 'user_id' => auth('api')->id()]);

        return response()->json([
            'data'    => $this->formatOrder($order->load('worker.user')),
            'message' => 'Pesanan berhasil dibuat',
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $user  = auth('api')->user();
        $query = Order::with('worker.user');

        if ($user->is_worker && $user->workerProfile && $request->as !== 'customer') {
            $query->where('worker_id', $user->workerProfile->id);
        } else {
            $query->where('user_id', $user->id);
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10);

        return response()->json([
            'data' => $orders->map(fn($o) => $this->formatOrder($o)),
            'meta' => [
                'total'       => $orders->total(),
                'page'        => $orders->currentPage(),
                'limit'       => $orders->perPage(),
                'total_pages' => $orders->lastPage(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $user  = auth('api')->user();
        $order = Order::with('worker.user')->findOrFail($id);

        $isOwner  = $order->user_id === $user->id;
        $isWorker = $user->workerProfile && $order->worker_id === $user->workerProfile->id;

        if (!$isOwner && !$isWorker) {
            return response()->json(['error' => ['code' => 'FORBIDDEN', 'message' => 'Akses ditolak']], 403);
        }

        return response()->json(['data' => $this->formatOrder($order)]);
    }

    public function cancel(string $id): JsonResponse
    {
        $order = Order::where('user_id', auth('api')->id())->findOrFail($id);

        if ($order->status !== 'menunggu') {
            return response()->json([
                'error' => ['code' => 'CANNOT_CANCEL', 'message' => 'Pesanan hanya bisa dibatalkan saat status menunggu'],
            ], 422);
        }

        $order->update(['status' => 'dibatalkan']);

        return response()->json(['data' => $this->formatOrder($order), 'message' => 'Pesanan dibatalkan']);
    }

    public function confirm(string $id): JsonResponse
    {
        return $this->workerUpdateStatus($id, 'dikonfirmasi', 'menunggu', 'Pesanan dikonfirmasi');
    }

    public function complete(string $id): JsonResponse
    {
        $response = $this->workerUpdateStatus($id, 'selesai', 'dikonfirmasi', 'Pesanan selesai');

        if ($response->getStatusCode() === 200) {
            $order = Order::find(json_decode($response->getContent())->data->id ?? null);
            $order?->worker?->increment('completed_jobs');
        }

        return $response;
    }

    private function workerUpdateStatus(string $id, string $newStatus, string $required, string $message): JsonResponse
    {
        $user   = auth('api')->user();
        $worker = $user->workerProfile;

        if (!$worker) {
            return response()->json(['error' => ['code' => 'NOT_WORKER', 'message' => 'Hanya pekerja yang dapat mengubah status ini']], 403);
        }

        $order = Order::where('worker_id', $worker->id)->findOrFail($id);

        if ($order->status !== $required) {
            return response()->json([
                'error' => ['code' => 'INVALID_STATUS', 'message' => "Status pesanan harus {$required}"],
            ], 422);
        }

        $order->update(['status' => $newStatus]);

        return response()->json(['data' => $this->formatOrder($order->load('worker.user')), 'message' => $message]);
    }

    private function formatOrder(Order $o): array
    {
        return [
            'id'         => $o->id,
            'worker'     => $o->worker ? [
                'id'        => $o->worker->id,
                'nama'      => $o->worker->user?->nama,
                'specialty' => $o->worker->specialty,
                'image_url' => $o->worker->image_url,
            ] : null,
            'tanggal'    => $o->tanggal,
            'waktu'      => $o->waktu,
            'deskripsi'  => $o->deskripsi,
            'alamat'     => $o->alamat,
            'telepon'    => $o->telepon,
            'status'     => $o->status,
            'created_at' => $o->created_at,
        ];
    }
}
