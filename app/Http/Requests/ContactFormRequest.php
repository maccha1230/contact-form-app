<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    /**
     * リクエストを許可する
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 入力チェックのルール
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|integer|in:1,2,3',
            'email' => 'required|string|email|max:255',
            'tel' => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'integer|exists:tags,id',
            'detail' => 'required|string|max:120',
        ];
    }
}
