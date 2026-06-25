<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Models\WorkerProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'worker_id'  => 'required|string|exists:worker_profiles,id',
            'tanggal'    => 'required|date|after:today',
            'waktu'      => 'required|date_format:H:i',
            'deskripsi'  => 'nullable|string',
            'alamat'     => 'required|string',
            'telepon'    => 'required|string|max:20',
        ]);

        $user   = auth('api')->user();
        $worker = WorkerProfile::find($validated['worker_id']);

        if ($worker && $worker->user_id === $user->id) {
            return response()->json([
                'error' => ['code' => 'SELF_ORDER', 'message' => 'Anda tidak dapat memesan jasa diri sendiri.'],
            ], 422);
        }

        $order = Order::create([...$validated, 'user_id' => $user->id]);
        $this->logStatusChange($order, null, 'menunggu');

        return response()->json([
            'data'    => $this->formatOrder($order->load('worker.user')),
            'message' => 'Pesanan berhasil dibuat',
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $user  = auth('api')->user();
        $query = Order::with('worker.user', 'user');

        if ($user->is_worker && $user->workerProfile && $request->as !== 'customer') {
            $query->where('worker_id', $user->workerProfile->id);
        } else {
            $query->where('user_id', $user->id);
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        // Auto-expire pesanan menunggu yang sudah lewat jadwal
        Order::where('status', 'menunggu')
            ->whereRaw("CONCAT(tanggal, ' ', waktu) < NOW()")
            ->update(['status' => 'kadaluarsa']);

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

    public function cancel(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $order = Order::where('user_id', auth('api')->id())->findOrFail($id);

        if ($order->status !== 'menunggu') {
            return response()->json([
                'error' => ['code' => 'CANNOT_CANCEL', 'message' => 'Pesanan hanya bisa dibatalkan saat status menunggu'],
            ], 422);
        }

        $order->update([
            'status'               => 'dibatalkan',
            'cancellation_reason'  => $validated['reason'],
        ]);
        $this->logStatusChange($order, 'menunggu', 'dibatalkan', $validated['reason']);

        return response()->json(['data' => $this->formatOrder($order), 'message' => 'Pesanan dibatalkan']);
    }

    public function confirm(string $id): JsonResponse
    {
        $user   = auth('api')->user();
        $worker = $user->workerProfile;

        if (!$worker) {
            return response()->json(['error' => ['code' => 'NOT_WORKER', 'message' => 'Hanya pekerja yang dapat mengkonfirmasi pesanan']], 403);
        }

        $order = Order::where('worker_id', $worker->id)->findOrFail($id);

        if ($order->status === 'menunggu' && $this->isExpired($order)) {
            $order->update(['status' => 'kadaluarsa']);
            $this->logStatusChange($order, 'menunggu', 'kadaluarsa', 'Otomatis kadaluarsa karena melewati jadwal.');
            return response()->json([
                'error' => ['code' => 'EXPIRED', 'message' => 'Pesanan ini sudah kadaluarsa karena melewati jadwal yang ditentukan.'],
            ], 422);
        }

        if ($order->status !== 'menunggu') {
            $msg = match($order->status) {
                'dibatalkan' => 'Pesanan ini sudah dibatalkan oleh pelanggan.',
                'kadaluarsa' => 'Pesanan ini sudah kadaluarsa.',
                default      => 'Status pesanan tidak valid untuk operasi ini.',
            };
            return response()->json(['error' => ['code' => 'INVALID_STATUS', 'message' => $msg]], 422);
        }

        $order->update(['status' => 'dikonfirmasi']);
        $this->logStatusChange($order, 'menunggu', 'dikonfirmasi');

        return response()->json(['data' => $this->formatOrder($order->load('worker.user', 'user')), 'message' => 'Pesanan dikonfirmasi']);
    }

    public function complete(string $id): JsonResponse
    {
        $order = Order::with('worker.user', 'user')->where('user_id', auth('api')->id())->findOrFail($id);

        if ($order->status !== 'dikonfirmasi') {
            return response()->json([
                'error' => ['code' => 'INVALID_STATUS', 'message' => 'Pesanan harus dikonfirmasi dulu sebelum diselesaikan'],
            ], 422);
        }

        $order->update(['status' => 'selesai']);
        $order->worker?->increment('completed_jobs');
        $this->logStatusChange($order, 'dikonfirmasi', 'selesai');

        return response()->json(['data' => $this->formatOrder($order), 'message' => 'Pesanan selesai']);
    }

    public function logs(string $id): JsonResponse
    {
        $user  = auth('api')->user();
        $order = Order::findOrFail($id);

        $isOwner  = $order->user_id === $user->id;
        $isWorker = $user->workerProfile && $order->worker_id === $user->workerProfile->id;

        if (!$isOwner && !$isWorker) {
            return response()->json(['error' => ['code' => 'FORBIDDEN', 'message' => 'Akses ditolak']], 403);
        }

        $logs = OrderStatusLog::with('actor')
            ->where('order_id', $id)
            ->oldest()
            ->get()
            ->map(fn($l) => [
                'from_status' => $l->from_status,
                'to_status'   => $l->to_status,
                'note'        => $l->note,
                'actor'       => $l->actor?->nama ?? 'Sistem',
                'created_at'  => $l->created_at,
            ]);

        return response()->json(['data' => $logs]);
    }

    private function logStatusChange(Order $order, ?string $from, string $to, ?string $note = null): void
    {
        OrderStatusLog::create([
            'order_id'    => $order->id,
            'changed_by'  => auth('api')->id(),
            'from_status' => $from,
            'to_status'   => $to,
            'note'        => $note,
        ]);
    }

    private function isExpired(Order $order): bool
    {
        return \Carbon\Carbon::now()->gt(
            \Carbon\Carbon::parse("{$order->tanggal} {$order->waktu}")
        );
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
            $message = $order->status === 'dibatalkan'
                ? "Pesanan ini sudah dibatalkan oleh pelanggan."
                : "Status pesanan tidak valid untuk operasi ini.";

            return response()->json([
                'error' => ['code' => 'INVALID_STATUS', 'message' => $message],
            ], 422);
        }

        $order->update(['status' => $newStatus]);

        return response()->json(['data' => $this->formatOrder($order->load('worker.user')), 'message' => $message]);
    }

    private function formatOrder(Order $o): array
    {
        return [
            'id'         => $o->id,
            'customer'   => $o->user ? [
                'id'     => $o->user->id,
                'nama'   => $o->user->nama,
                'telepon'=> $o->user->telepon,
            ] : null,
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
            'status'               => $o->status,
            'cancellation_reason'  => $o->cancellation_reason,
            'created_at'           => $o->created_at,
        ];
    }
}
