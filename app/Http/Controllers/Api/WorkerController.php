<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkerProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = WorkerProfile::with(['categories', 'tags', 'user']);

        if ($q = $request->q) {
            $query->where(function ($b) use ($q) {
                $b->where('specialty', 'like', "%{$q}%")
                    ->orWhere('bio', 'like', "%{$q}%")
                    ->orWhereHas('tags', fn($t) => $t->where('tag', 'like', "%{$q}%"))
                    ->orWhereHas('user', fn($u) => $u->where('nama', 'like', "%{$q}%"));
            });
        }

        if ($lokasi = $request->lokasi) {
            $query->where('location', 'like', "%{$lokasi}%");
        }

        if ($category = $request->category) {
            $query->whereHas('categories', fn($c) => $c->where('categories.id', $category));
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $limit   = min((int) ($request->limit ?? 12), 50);
        $workers = $query->paginate($limit);

        return response()->json([
            'data' => $workers->map(fn($w) => $this->formatWorker($w)),
            'meta' => [
                'total'       => $workers->total(),
                'page'        => $workers->currentPage(),
                'limit'       => $workers->perPage(),
                'total_pages' => $workers->lastPage(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $worker = WorkerProfile::with(['categories', 'tags', 'services', 'user'])->findOrFail($id);

        return response()->json(['data' => $this->formatWorker($worker, full: true)]);
    }

    public function reviews(string $id): JsonResponse
    {
        $worker  = WorkerProfile::findOrFail($id);
        $reviews = $worker->reviews()->with('user')->latest('created_at')->paginate(10);

        return response()->json([
            'data' => $reviews->map(fn($r) => [
                'id'         => $r->id,
                'user'       => ['id' => $r->user?->id, 'nama' => $r->user?->nama],
                'rating'     => $r->rating,
                'comment'    => $r->comment,
                'created_at' => $r->created_at,
            ]),
            'meta' => [
                'total'       => $reviews->total(),
                'page'        => $reviews->currentPage(),
                'limit'       => $reviews->perPage(),
                'total_pages' => $reviews->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'specialty'        => 'required|string|max:100',
            'bio'              => 'nullable|string',
            'location'         => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0',
            'response_time'    => 'nullable|string|max:50',
            'category_ids'     => 'required|array|min:1',
            'category_ids.*'   => 'string|exists:categories,id',
            'tags'             => 'nullable|array',
            'tags.*'           => 'string|max:50',
            'services'         => 'nullable|array',
            'services.*.name'  => 'required|string|max:100',
            'services.*.price' => 'nullable|integer',
            'services.*.unit'  => 'nullable|string|max:30',
        ]);

        $user = auth('api')->user();

        if ($user->workerProfile) {
            return response()->json([
                'error' => ['code' => 'ALREADY_WORKER', 'message' => 'Anda sudah terdaftar sebagai pekerja'],
            ], 409);
        }

        $worker = WorkerProfile::create([
            'user_id'          => $user->id,
            'specialty'        => $validated['specialty'],
            'bio'              => $validated['bio'] ?? null,
            'location'         => $validated['location'] ?? null,
            'experience_years' => $validated['experience_years'] ?? 0,
            'response_time'    => $validated['response_time'] ?? null,
        ]);

        $worker->categories()->sync($validated['category_ids']);

        foreach ($validated['tags'] ?? [] as $tag) {
            $worker->tags()->create(['tag' => $tag]);
        }

        foreach ($validated['services'] ?? [] as $svc) {
            $worker->services()->create($svc);
        }

        $user->update(['is_worker' => true]);

        return response()->json([
            'data'    => $this->formatWorker($worker->load(['categories', 'tags', 'services', 'user']), full: true),
            'message' => 'Berhasil mendaftar sebagai pekerja',
        ], 201);
    }

    public function update(Request $request): JsonResponse
    {
        $user   = auth('api')->user();
        $worker = $user->workerProfile;

        if (!$worker) {
            return response()->json([
                'error' => ['code' => 'NOT_WORKER', 'message' => 'Profil pekerja tidak ditemukan'],
            ], 404);
        }

        $validated = $request->validate([
            'specialty'        => 'sometimes|string|max:100',
            'bio'              => 'sometimes|nullable|string',
            'location'         => 'sometimes|nullable|string|max:100',
            'experience_years' => 'sometimes|integer|min:0',
            'response_time'    => 'sometimes|nullable|string|max:50',
            'category_ids'     => 'sometimes|array|min:1',
            'category_ids.*'   => 'string|exists:categories,id',
            'tags'             => 'sometimes|array',
            'tags.*'           => 'string|max:50',
            'services'         => 'sometimes|array',
            'services.*.name'  => 'required_with:services|string|max:100',
            'services.*.price' => 'nullable|integer',
            'services.*.unit'  => 'nullable|string|max:30',
        ]);

        $worker->update(collect($validated)->only(['specialty', 'bio', 'location', 'experience_years', 'response_time'])->toArray());

        if (isset($validated['category_ids'])) {
            $worker->categories()->sync($validated['category_ids']);
        }

        if (isset($validated['tags'])) {
            $worker->tags()->delete();
            foreach ($validated['tags'] as $tag) {
                $worker->tags()->create(['tag' => $tag]);
            }
        }

        if (isset($validated['services'])) {
            $worker->services()->delete();
            foreach ($validated['services'] as $svc) {
                $worker->services()->create($svc);
            }
        }

        return response()->json([
            'data'    => $this->formatWorker($worker->load(['categories', 'tags', 'services', 'user']), full: true),
            'message' => 'Profil pekerja berhasil diperbarui',
        ]);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $request->validate(['status' => 'required|in:available,busy']);

        $worker = auth('api')->user()->workerProfile;

        if (!$worker) {
            return response()->json([
                'error' => ['code' => 'NOT_WORKER', 'message' => 'Profil pekerja tidak ditemukan'],
            ], 404);
        }

        $worker->update(['status' => $request->status]);

        return response()->json(['data' => ['status' => $worker->status], 'message' => 'Status berhasil diperbarui']);
    }

    private function formatWorker(WorkerProfile $w, bool $full = false): array
    {
        $data = [
            'id'               => $w->id,
            'nama'             => $w->user?->nama,
            'specialty'        => $w->specialty,
            'location'         => $w->location,
            'status'           => $w->status,
            'rating'           => $w->rating,
            'completed_jobs'   => $w->completed_jobs,
            'experience_years' => $w->experience_years,
            'response_time'    => $w->response_time,
            'image_url'        => $w->image_url,
        ];

        if ($full) {
            $data['bio']        = $w->bio;
            $data['categories'] = $w->categories->map(fn($c) => ['id' => $c->id, 'title' => $c->title]);
            $data['tags']       = $w->tags->pluck('tag');
            $data['services']   = $w->services?->map(fn($s) => ['name' => $s->name, 'price' => $s->price, 'unit' => $s->unit]);
        }

        return $data;
    }
}
