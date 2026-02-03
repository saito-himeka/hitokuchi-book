<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Support\Facades\Storage; 

class BookController extends Controller
{
    // 投稿画面を表示する
    public function create()
    {
        $genres = Genre::all(); // 全ジャンルを取得
        return view('admin.books.create', compact('genres'));
    }

    // データをデータベースに保存する
    public function store(Request $request)
    {
        // 1. 全データを受け取る
        $data = $request->all();

        // 2. 本の表紙画像（image_path）の処理
        if ($request->hasFile('image')) {
            // 'covers' フォルダに保存
            $path = $request->file('image')->store('covers', 'public');
            $data['image_path'] = $path;
        }

        // 3. 図解画像（diagram_path）の処理
        if ($request->hasFile('diagram')) {
            // 'diagrams' フォルダに保存
            $path = $request->file('diagram')->store('diagrams', 'public');
            $data['diagram_path'] = $path;
        }

        // 4. データベースに保存
        Book::create($data);

        return redirect()->route('top')->with('message', '画像付きで投稿が完了しました！');
    }

    // 編集画面を表示
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $genres = Genre::all(); // 編集画面でも必要
        return view('admin.books.edit', compact('book', 'genres'));
    }


    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $data = $request->all();

        // 1. 表紙画像の更新とお掃除
        if ($request->hasFile('image')) {
            // 古いファイルがあれば削除
            if ($book->image_path) {
                Storage::disk('public')->delete($book->image_path);
            }
            $data['image_path'] = $request->file('image')->store('covers', 'public');
        }

        // 2. 図解画像の更新とお掃除
        if ($request->hasFile('diagram')) {
            // 古いファイルがあれば削除
            if ($book->diagram_path) {
                Storage::disk('public')->delete($book->diagram_path);
            }
            $data['diagram_path'] = $request->file('diagram')->store('diagrams', 'public');
        }

        $book->update($data);
        return redirect()->route('top')->with('message', '画像をお掃除して更新しました！');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // 3. データを消す前に、保存されている画像ファイルをすべて削除
        if ($book->image_path) {
            Storage::disk('public')->delete($book->image_path);
        }
        if ($book->diagram_path) {
            Storage::disk('public')->delete($book->diagram_path);
        }

        $book->delete();
        return redirect()->route('top')->with('message', '画像も含めて完全に削除しました！');
    }

}