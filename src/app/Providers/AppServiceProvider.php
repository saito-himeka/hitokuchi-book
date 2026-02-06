<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema; // ★ これを追加
use App\Models\Genre;
use App\Models\Book;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {

        // 本番環境（Renderなど）ならHTTPSを強制する
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // 1. ジャンル情報を共有（テーブルがある場合のみ）
        if (Schema::hasTable('genres')) {
            $genres = Genre::all();
            View::share('genres', $genres);
        }

        // 2. 最新の投稿3件を共有（テーブルがある場合のみ）
        if (Schema::hasTable('books')) {
            $latest_books = Book::latest()->take(3)->get();
            View::share('latest_books', $latest_books);
        }

        Paginator::useBootstrap();
    }
}