<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
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

        // テーブルが存在するか確認する安全装置を追加
        if (Schema::hasTable('genres')) {
            $genres = Genre::all();
            View::share('genres', $genres);
        }

        // 全てのビュー（.blade.php）に $genres を共有する
        View::share('genres', Genre::all());

        // ★ 最新の投稿3件を全てのビューに共有
        View::share('latest_books', Book::latest()->take(3)->get());

        Paginator::useBootstrap();
    }
}
