<?php

namespace App\Services;

use App\Models\Contact;

class ContactSearchService
{
    /**
     * 検索条件に合うお問い合わせを取得する
     */
    public function search(array $filters)
    {
        // カテゴリーとタグをあらかじめ読み込む
        $query = Contact::with(['category', 'tags']);

        // 名前・メールアドレスで検索
        if (! empty($filters['keyword'])) {
            $keyword = $filters['keyword'];

            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // 性別で検索
        if (
            ! empty($filters['gender'])
            && $filters['gender'] !== '0'
        ) {
            $query->where('gender', $filters['gender']);
        }

        // カテゴリーで検索
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // 日付で検索
        if (! empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        // 新しいお問い合わせから10件ずつ表示
        return $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
    }
}
