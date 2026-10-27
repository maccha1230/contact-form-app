<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// お問い合わせ入力画面
Route::get('/', [ContactController::class, 'index'])
    ->name('contact.index');

// 確認画面
Route::post('/contacts/confirm', [ContactController::class, 'confirm'])
    ->name('contact.confirm');

// 確認画面からの送信処理
Route::post('/contacts', [ContactController::class, 'send'])
    ->name('contact.store');

// 送信完了画面
Route::get('/thanks', [ContactController::class, 'thanks'])
    ->name('contact.thanks');

// 管理画面（ログイン必須）
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('auth')
    ->name('admin.index');

// お問い合わせ詳細ページ（ログイン必須）
Route::get('/admin/contacts/{contact}', [AdminController::class, 'show'])
    ->middleware('auth')
    ->name('admin.show');

// プロフィール編集
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// タグ編集ページ（ログイン必須）
Route::middleware('auth')->group(function () {
    Route::get('/admin/tags/{tag}/edit', [TagController::class, 'edit'])
        ->name('admin.tags.edit');

    Route::put('/admin/tags/{tag}', [TagController::class, 'update'])
        ->name('admin.tags.update');
});
