<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use App\Services\ContactSearchService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(
        Request $request,
        ContactSearchService $contactSearchService
    ) {
        // 検索処理をサービスクラスに任せる
        $contacts = $contactSearchService->search(
            $request->only([
                'keyword',
                'gender',
                'category_id',
                'date',
            ])
        );

        // 画面に表示するカテゴリーとタグを取得
        $categories = Category::all();
        $tags = Tag::all();

        // 検索結果を画面に返す
        return view('admin.index', compact(
            'contacts',
            'categories',
            'tags'
        ));
    }

    // お問い合わせ詳細ページ
    public function show($id)
    {
        $contact = Contact::with(['category', 'tags'])
            ->findOrFail($id);

        return view('admin.show', compact('contact'));
    }
}
