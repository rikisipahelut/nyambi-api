<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function store(Request $request, string $orderId): JsonResponse
    {
        $validated = $request->validate([
            'tipe'      => 'required|string|in:pekerjaan_tidak_sesuai,pekerja_tidak_hadir,pekerjaan_tidak_selesai,customer_tidak_ada,lainnya',
            'deskripsi' => 'required|string|min:10|max:2000',
        ]);

        [$order, $user, $role] = $this->resolveParties($orderId);

        if (!in_array($order->status, ['dikonfirmasi', 'selesai'])) {
            return response()->json([
                'error' => ['code' => 'ORDER_NOT_ELIGIBLE', 'message' => 'Komplain hanya bisa diajukan pada pesanan yang sudah dikonfirmasi atau selesai.'],
            ], 422);
        }

        if (Complaint::where('order_id', $orderId)->where('status', 'terbuka')->exists()) {
            return response()->json([
                'error' => ['code' => 'COMPLAINT_EXISTS', 'message' => 'Sudah ada komplain aktif untuk pesanan ini.'],
            ], 409);
        }

        $complaint = Complaint::create([
            'order_id'  => $orderId,
            'filed_by'  => $user->id,
            'filed_as'  => $role,
            'tipe'      => $validated['tipe'],
            'deskripsi' => $validated['deskripsi'],
            'status'    => 'terbuka',
        ]);

        $this->notifyOtherParty($order, $role, [
            'icon'  => 'gavel',
            'title' => 'Komplain Baru',
            'body'  => 'Ada komplain baru pada pesanan Anda.',
        ]);

        return response()->json([
            'data'    => $this->formatComplaint($complaint->load('filer')),
            'message' => 'Komplain berhasil diajukan',
        ], 201);
    }

    public function show(string $orderId): JsonResponse
    {
        $this->resolveParties($orderId);

        $complaint = Complaint::with(['filer', 'responses.author'])
            ->where('order_id', $orderId)
            ->latest()
            ->first();

        return response()->json(['data' => $complaint ? $this->formatComplaint($complaint) : null]);
    }

    public function addResponse(Request $request, string $orderId): JsonResponse
    {
        $validated = $request->validate([
            'pesan' => 'required|string|min:1|max:2000',
        ]);

        [$order, $user, $role] = $this->resolveParties($orderId);

        $complaint = Complaint::where('order_id', $orderId)
            ->where('status', 'terbuka')
            ->first();

        if (!$complaint) {
            return response()->json([
                'error' => ['code' => 'NO_OPEN_COMPLAINT', 'message' => 'Tidak ada komplain aktif untuk pesanan ini.'],
            ], 422);
        }

        $response = ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'user_id'      => $user->id,
            'sent_as'      => $role,
            'pesan'        => $validated['pesan'],
        ]);

        $this->notifyOtherParty($order, $role, [
            'icon'  => 'forum',
            'title' => 'Balasan Komplain',
            'body'  => 'Ada balasan baru pada komplain pesanan Anda.',
        ]);

        return response()->json([
            'data' => [
                'id'          => $response->id,
                'sent_as'     => $response->sent_as,
                'author_name' => $user->nama,
                'pesan'       => $response->pesan,
                'created_at'  => $response->created_at,
            ],
            'message' => 'Balasan berhasil dikirim',
        ], 201);
    }

    public function resolve(string $orderId): JsonResponse
    {
        return $this->updateStatus($orderId, 'diselesaikan', [
            'icon'  => 'check_circle',
            'title' => 'Komplain Diselesaikan',
            'body'  => 'Komplain pada pesanan Anda telah diselesaikan.',
        ]);
    }

    public function close(string $orderId): JsonResponse
    {
        return $this->updateStatus($orderId, 'ditutup', [
            'icon'  => 'cancel',
            'title' => 'Komplain Ditutup',
            'body'  => 'Komplain pada pesanan Anda telah ditutup.',
        ]);
    }

    private function updateStatus(string $orderId, string $newStatus, array $notif): JsonResponse
    {
        [$order, , $role] = $this->resolveParties($orderId);

        $complaint = Complaint::where('order_id', $orderId)
            ->where('status', 'terbuka')
            ->first();

        if (!$complaint) {
            return response()->json([
                'error' => ['code' => 'NO_OPEN_COMPLAINT', 'message' => 'Tidak ada komplain aktif untuk pesanan ini.'],
            ], 422);
        }

        $complaint->update(['status' => $newStatus]);

        $this->notifyOtherParty($order, $role, $notif);

        return response()->json([
            'data'    => $this->formatComplaint($complaint->load('filer')),
            'message' => 'Status komplain diperbarui',
        ]);
    }

    private function resolveParties(string $orderId): array
    {
        $user  = auth('api')->user();
        $order = Order::with('worker')->findOrFail($orderId);

        $isCustomer = $order->user_id === $user->id;
        $isWorker   = $user->workerProfile && $order->worker_id === $user->workerProfile->id;

        if (!$isCustomer && !$isWorker) {
            abort(403, 'Akses ditolak');
        }

        return [$order, $user, $isCustomer ? 'customer' : 'worker'];
    }

    private function notifyOtherParty(Order $order, string $currentRole, array $notif): void
    {
        $recipientId = $currentRole === 'customer'
            ? $order->worker?->user_id
            : $order->user_id;

        if (!$recipientId) return;

        Notification::create([
            'user_id' => $recipientId,
            'icon'    => $notif['icon'],
            'title'   => $notif['title'],
            'body'    => $notif['body'],
            'href'    => "/komplain/{$order->id}",
            'is_read' => false,
        ]);
    }

    private function formatComplaint(Complaint $c): array
    {
        return [
            'id'          => $c->id,
            'order_id'    => $c->order_id,
            'filed_as'    => $c->filed_as,
            'filer_name'  => $c->filer?->nama ?? 'Pengguna',
            'tipe'        => $c->tipe,
            'deskripsi'   => $c->deskripsi,
            'status'      => $c->status,
            'created_at'  => $c->created_at,
            'responses'   => $c->relationLoaded('responses')
                ? $c->responses->map(fn($r) => [
                    'id'          => $r->id,
                    'sent_as'     => $r->sent_as,
                    'author_name' => $r->author?->nama ?? 'Pengguna',
                    'pesan'       => $r->pesan,
                    'created_at'  => $r->created_at,
                ])->values()
                : [],
        ];
    }
}
