<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ja_JP');

        // 要件で指定されたカテゴリだけを取得
        $categories = Category::whereIn('content', [
            '商品のお届けについて',
            '商品の交換について',
            '商品トラブル',
            'ショップへのお問い合わせ',
            'その他',
        ])->get();

        // 要件で指定されたタグだけを取得
        $tags = Tag::whereIn('name', [
            '質問',
            '要望',
            '不具合報告',
            'ご意見',
            'その他',
        ])->get();

        if ($categories->count() !== 5 || $tags->count() !== 5) {
            throw new \RuntimeException(
                'カテゴリまたはタグが不足しています。先にSeederの設定を確認してください。'
            );
        }

        DB::transaction(function () use ($faker, $categories, $tags) {
            for ($i = 1; $i <= 20; $i++) {
                $email = "contact{$i}@example.test";

                // このSeederが以前作成したデータだけを削除
                $oldContact = Contact::where('email', $email)->first();

                if ($oldContact) {
                    $oldContact->tags()->detach();
                    $oldContact->delete();
                }

                $contact = Contact::create([
                    'first_name' => $faker->firstName(),
                    'last_name' => $faker->lastName(),
                    'gender' => $faker->numberBetween(1, 3),
                    'email' => $email,
                    'tel' => $faker->numerify('###########'),
                    'address' => $faker->prefecture()
                        .$faker->city()
                        .$faker->streetAddress(),
                    'building' => $faker->boolean(60)
                        ? $faker->secondaryAddress()
                        : null,
                    'category_id' => $categories->random()->id,
                    'detail' => Str::limit(
                        $faker->realText(100),
                        120,
                        ''
                    ),
                ]);

                // 1〜3個のタグをランダムに紐づける
                $tagIds = $tags->random(
                    $faker->numberBetween(1, 3)
                )->pluck('id')->all();

                $contact->tags()->attach($tagIds);
            }
        });
    }
}
