<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\IndexContactRequest;
use App\Http\Requests\Api\V1\StoreContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * お問い合わせ一覧を取得する
     */
    public function index(IndexContactRequest $request)
    {
        // 入力チェックを通過したパラメータだけ取得する
        $params = $request->validated();

        $query = Contact::with(['category', 'tags']);

        // 姓・名・メールアドレスで部分一致検索
        if (! empty($params['keyword'])) {
            $keyword = $params['keyword'];

            $query->where(function ($q) use ($keyword) {
                $q->where('last_name', 'like', "%{$keyword}%")
                    ->orWhere('first_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // 性別で絞り込み
        if (isset($params['gender'])) {
            $query->where('gender', $params['gender']);
        }

        // カテゴリIDで絞り込み
        if (isset($params['category_id'])) {
            $query->where('category_id', $params['category_id']);
        }

        // 作成日で絞り込み
        if (! empty($params['date'])) {
            $query->whereDate('created_at', $params['date']);
        }

        // 1ページあたりの件数（デフォルト20件）
        $perPage = $params['per_page'] ?? 20;

        // ページ番号（デフォルト1ページ目）
        $page = $params['page'] ?? 1;

        $contacts = $query
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return ContactResource::collection($contacts);
    }

    /**
     * お問い合わせを登録する
     */
    public function store(StoreContactRequest $request)
    {
        $params = $request->validated();

        $contact = new Contact;
        $contact->last_name = $params['last_name'];
        $contact->first_name = $params['first_name'];
        $contact->email = $params['email'];
        $contact->gender = (string) $params['gender'];
        $contact->postcode = $params['postcode'];
        $contact->address = $params['address'];
        $contact->building = $params['building'] ?? null;
        $contact->category = Category::findOrFail($params['category_id'])->content;
        $contact->category_id = $params['category_id'];
        $contact->detail = $params['detail'];
        $contact->save();

        return response()->json([
            'message' => 'お問い合わせを登録しました。',
            'data' => $contact,
        ], 201);
    }

    /**
     * お問い合わせ詳細を取得する
     */
    public function show(Contact $contact)
    {
        $contact->load(['category', 'tags']);

        return new ContactResource($contact);
    }

    /**
     * お問い合わせを更新する
     */
    public function update(Request $request, Contact $contact)
    {
        $params = $request->validate([
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255'],
            'gender' => ['sometimes', 'required', 'integer', 'in:1,2,3'],
            'postcode' => ['sometimes', 'required', 'string', 'max:8'],
            'address' => ['sometimes', 'required', 'string', 'max:255'],
            'building' => ['sometimes', 'nullable', 'string', 'max:255'],
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'detail' => ['sometimes', 'required', 'string'],
        ]);

        if (isset($params['category_id'])) {
            $params['category'] = Category::findOrFail(
                $params['category_id']
            )->content;
        }

        $contact->fill($params);
        $contact->save();

        return response()->json([
            'message' => 'お問い合わせを更新しました。',
            'data' => $contact,
        ]);
    }

    /**
     * お問い合わせを削除する
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json([
            'message' => 'お問い合わせを削除しました。',
        ]);
    }
}
