<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::withCount('workers')->get();

        return response()->json([
            'data' => $categories->map(fn($c) => [
                'id'           => $c->id,
                'icon'         => $c->icon,
                'title'        => $c->title,
                'worker_count' => $c->workers_count,
            ]),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $category = Category::withCount('workers')->findOrFail($id);

        return response()->json([
            'data' => [
                'id'           => $category->id,
                'icon'         => $category->icon,
                'title'        => $category->title,
                'worker_count' => $category->workers_count,
            ],
        ]);
    }
}
