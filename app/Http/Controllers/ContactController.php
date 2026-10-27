<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class ContactController extends Controller
{
    // 入力画面
    public function index()
    {
        return view('contact.index', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    // 確認画面
    public function confirm(ContactFormRequest $request)
    {
        // 入力内容を取得
        $validated = $request->validated();

        // 選択されたカテゴリーを取得
        $category = Category::findOrFail(
            $validated['category_id']
        );

        // 選択されたタグを取得
        $tags = Tag::whereIn(
            'id',
            $validated['tag_ids'] ?? []
        )->get();

        return view('contact.confirm', [
            'validated' => $validated,
            'category' => $category,
            'tags' => $tags,
        ]);
    }

    // 送信処理
    public function send(ContactFormRequest $request)
    {
        $validated = $request->validated();

        // お問い合わせを保存
        $contact = Contact::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'tel' => $validated['tel'],
            'address' => $validated['address'],
            'building' => $validated['building'] ?? null,
            'category_id' => $validated['category_id'],
            'detail' => $validated['detail'],
        ]);

        // 選択されたタグを中間テーブルに保存
        $contact->tags()->sync($validated['tag_ids'] ?? []);

        // 送信完了画面を表示
        return view('contact.thanks');
    }

    // サンクスページ
    public function thanks()
    {
        return view('contact.thanks');
    }
}
