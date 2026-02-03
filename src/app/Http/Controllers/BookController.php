<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    // トップページ（一覧・検索・絞り込み）
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $genre_id = $request->input('genre_id'); // genre から genre_id に変更

        $query = Book::query();

        // キーワード検索
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('author', 'like', "%{$keyword}%");
            });
        }

        // ジャンル絞り込み（IDで検索）
        if ($genre_id) {
            $query->where('genre_id', $genre_id);
        }

        // 最新順 ＆ リレーション先（genre）をまとめて取得
        $books = $query->with('genre')->latest()->paginate(9)->withQueryString();

        // ★トップページのボタンに表示するために全ジャンルを取得
        $genres = \App\Models\Genre::all();

        return view('index', compact('books', 'genres'));
    }

    // 詳細ページ
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('show', compact('book'));
    }
}