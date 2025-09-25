<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $category = $this->categories->create($request->validated(), $request->user());

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

    public function destroy(Category $category, Request $request): JsonResponse
    {
        try {
            $this->categories->delete($category, $request->user());
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                return response()->json([
                    'message' => 'Não é possível excluir uma categoria que possui ofertas vinculadas.',
                ], HttpResponse::HTTP_CONFLICT);
            }

            throw $exception;
        } catch (AuthorizationException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], HttpResponse::HTTP_FORBIDDEN);
        }

        return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
    }
}
