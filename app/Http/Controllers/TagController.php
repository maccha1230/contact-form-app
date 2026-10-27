<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // タグ編集ページを表示
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    // タグ名を更新
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $tag->name = $validated['name'];
        $tag->save();

        return redirect('/admin');
    }
}
