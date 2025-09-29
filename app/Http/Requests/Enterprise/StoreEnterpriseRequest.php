<?php

namespace App\Http\Requests\Enterprise;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnterpriseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:100', 'unique:enterprises,name'],
            'description' => ['required', 'max:255'],
        ];
    }
}
