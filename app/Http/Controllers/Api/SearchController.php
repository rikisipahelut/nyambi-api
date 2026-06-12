<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\WorkerProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q      = $request->q ?? '';
        $lokasi = $request->lokasi ?? '';

        $workerQuery = WorkerProfile::with('user');

        if ($q) {
            $workerQuery->where(function ($b) use ($q) {
                $b->where('specialty', 'like', "%{$q}%")
                    ->orWhere('bio', 'like', "%{$q}%")
                    ->orWhereHas('tags', fn($t) => $t->where('tag', 'like', "%{$q}%"))
                    ->orWhereHas('user', fn($u) => $u->where('nama', 'like', "%{$q}%"));
            });
        }

        if ($lokasi) {
            $workerQuery->where('location', 'like', "%{$lokasi}%");
        }

        $workers = $workerQuery->limit(10)->get();

        $categoryQuery = Category::query();
        if ($q) {
            $categoryQuery->where('title', 'like', "%{$q}%")->orWhere('id', 'like', "%{$q}%");
        }

        $categories = $categoryQuery->withCount('workers')->limit(5)->get();

        return response()->json([
            'workers' => $workers->map(fn($w) => [
                'id'        => $w->id,
                'nama'      => $w->user?->nama,
                'specialty' => $w->specialty,
                'location'  => $w->location,
                'rating'    => $w->rating,
                'status'    => $w->status,
            ]),
            'categories' => $categories->map(fn($c) => [
                'id'           => $c->id,
                'title'        => $c->title,
                'worker_count' => $c->workers_count,
            ]),
        ]);
    }
}
