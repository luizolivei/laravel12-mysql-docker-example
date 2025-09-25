<?php

namespace App\Interfaces\Http\Requests\Category;

use App\Domain\Categories\Entities\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $routeCategory = $this->route('category');
        $categoryId = $routeCategory instanceof Category
            ? $routeCategory->getKey()
            : (int) $routeCategory;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}
