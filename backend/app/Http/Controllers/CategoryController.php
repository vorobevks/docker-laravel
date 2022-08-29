<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryFormRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function getAll(CategoryService $categoryService): JsonResponse
    {
        return response()->json($categoryService->getAll());
    }

    public function create(CreateCategoryFormRequest $request, CategoryService $categoryService): JsonResponse
    {
        $category = $categoryService->create($request->getDto());
        if (!is_a($category, Category::class)) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании категории',
                'data' => []
            ], 422);
        }
        return response()->json($category);
    }
}
