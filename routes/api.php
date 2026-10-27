<?php

use App\Http\Controllers\Api\V1\ContactController;
use Illuminate\Support\Facades\Route;

// お問い合わせ一覧（検索・ページネーション付き）
Route::get('/v1/contacts', [ContactController::class, 'index']);

// お問い合わせ詳細（カテゴリ・タグ含む）
Route::get('/v1/contacts/{contact}', [ContactController::class, 'show']);

// お問い合わせ新規作成
Route::post('/v1/contacts', [ContactController::class, 'store']);

// お問い合わせ更新
Route::put('/v1/contacts/{contact}', [ContactController::class, 'update']);

// お問い合わせ削除
Route::delete('/v1/contacts/{contact}', [ContactController::class, 'destroy']);
