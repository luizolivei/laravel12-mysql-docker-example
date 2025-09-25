<?php

namespace App\Interfaces\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class OfferIndexRequest extends FormRequest
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
        return [
            'search' => ['nullable', 'string'],
        ];
    }
}
