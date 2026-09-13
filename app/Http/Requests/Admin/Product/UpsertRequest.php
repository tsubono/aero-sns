<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'point' => ['required', 'integer', 'min:1'],
            'popularity_order' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'contents' => ['nullable', 'string'],
            'file_type' => ['nullable', 'string', 'max:50'],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'account_texts' => ['nullable', 'array'],
            'account_texts.*.id' => ['nullable', 'integer'],
            'account_texts.*.text' => ['nullable', 'string'],
        ];
    }
}
