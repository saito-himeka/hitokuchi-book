<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Genre;
// Cloudinaryを使うための命令を追加
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; 

class BookController extends Controller
{
    public function create()
    {
        $genres = Genre::all();
        return view('admin.books.create', compact('genres'));
    }

    // データを保存する
    public function store(Request $request)
    {
        $data = $request->all();

        // 1. 本の表紙画像をCloudinaryに保存
        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), ['folder' => 'covers'])->getSecurePath();
            $data['image_path'] = $uploadedFileUrl;
        }

        // 2. 図解画像をCloudinaryに保存
        if ($request->hasFile('diagram')) {
            $uploadedDiagramUrl = Cloudinary::upload($request->file('diagram')->getRealPath(), ['folder' => 'diagrams'])->getSecurePath();
            $data['diagram_path'] = $uploadedDiagramUrl;
        }

        Book::create($data);

        return redirect()->route('top')->with('message', 'Cloudinaryに画像を保存して投稿しました！');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $genres = Genre::all();
        return view('admin.books.edit', compact('book', 'genres'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $data = $request->all();

        // 1. 表紙画像の更新（Cloudinaryは上書きURLが生成されるため、古いファイル削除は一旦省略してOKです）
        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), ['folder' => 'covers'])->getSecurePath();
            $data['image_path'] = $uploadedFileUrl;
        }

        // 2. 図解画像の更新
        if ($request->hasFile('diagram')) {
            $uploadedDiagramUrl = Cloudinary::upload($request->file('diagram')->getRealPath(), ['folder' => 'diagrams'])->getSecurePath();
            $data['diagram_path'] = $uploadedDiagramUrl;
        }

        $book->update($data);
        return redirect()->route('top')->with('message', '画像を新しくして更新しました！');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        
        // Cloudinary上の画像削除は少し複雑なため、まずはDBとアプリ上の削除を優先します
        $book->delete();
        
        return redirect()->route('top')->with('message', '投稿を削除しました！');
    }
}