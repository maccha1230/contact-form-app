<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class IndexContactRequest extends FormRequest
{
    /**
     * APIへのアクセスを許可する
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * リクエストパラメータの入力チェック
     */
    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'integer', 'in:1,2,3'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'date' => ['nullable', 'date'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * バリデーションエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'gender.in' => '性別の値が不正です',
            'gender.integer' => '性別は整数で指定してください',
        ];
    }
}
