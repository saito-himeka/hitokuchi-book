<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Genre;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. あなたのアカウントを登録（メールとパスワードのみ）
        User::updateOrCreate(
            ['email' => 'your-email@example.com'], // ★実際のアドレスに書き換え
            [
                'name' => '管理者', // 名前項目はDB上必須なことが多いため、固定値を入れています
                'password' => Hash::make('your-password'), // ★実際のパスワードに書き換え
            ]
        );

        // 2. 指定のジャンルデータを一括登録
        $genres = [
            '小説',
            '暮らし',
            '絵本',
            'ビジネス・教養',
            '人文・思想',
            'エッセイ',
            '政治・社会',
            'アート・デザイン'
        ];

        foreach ($genres as $genreName) {
            Genre::updateOrCreate(
                ['name' => $genreName],
                ['name' => $genreName]
            );
        }
    }
}