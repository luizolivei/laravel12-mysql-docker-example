<?php

namespace App\Interfaces\Http\Controllers\Api;

use App\Application\Categories\Services\CategoryService;
use App\Domain\Categories\Entities\Category;
use App\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Category\CategoryStoreRequest;
use App\Interfaces\Http\Requests\Category\CategoryUpdateRequest;
use App\Interfaces\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categories,
    ) {
    }

    public function index(): JsonResponse
    {
        $categories = $this->categories->list();

        return CategoryResource::collection($categories)
            ->response()
            ->setStatusCode(HttpResponse::HTTP_OK);
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $category = $this->categories->create($request->validated());

        return CategoryResource::make($category)
            ->response()
            ->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        $category = $this->categories->update($category, $request->validated());

        return CategoryResource::make($category)
            ->response()
            ->setStatusCode(HttpResponse::HTTP_OK);
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->categories->delete($category);

        return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
    }
}
