@extends('layouts.app')

@section('content')
<div class="auth-content">
    <h1 class="page-title">要約の編集</h1>

    <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label>本の表紙画像（トップページ用）</label>
            @if($book->image_path)
                <div class="current-image-preview">
                    <p>現在の表紙：</p>
                    <img src="{{ asset('storage/' . $book->image_path) }}" width="100">
                </div>
            @endif
            <input type="file" name="image" accept="image/*">
        </div>

        <div class="form-group">
            <label>本のタイトル</label>
            <input type="text" name="title" value="{{ $book->title }}" required>
        </div>

        <div class="form-group">
            <label>著者名</label>
            <input type="text" name="author" value="{{ $book->author }}" required>
        </div>

        <div class="form-group">
            <label>ジャンル</label>
            <input type="text" name="genre" value="{{ $book->genre }}">
        </div>

        <div class="form-group">
            <label>要約本文</label>
            <textarea name="summary" rows="10" required>{{ $book->summary }}</textarea>
        </div>

        <div class="form-group">
            <label>アフィリエイトURL</label>
            <input type="url" name="affiliate_url" value="{{ $book->affiliate_url }}">
        </div>

        <div class="form-group">
            <label>図解画像（詳細ページ用）</label>
            @if($book->diagram_path) <div class="current-image-preview">
                    <p>現在の図解：</p>
                    <img src="{{ asset('storage/' . $book->diagram_path) }}" width="200"> </div>
            @endif
            <input type="file" name="diagram" accept="image/*">
        </div>

        <button type="submit" class="read-more">更新を保存する</button>
    </form>
</div>
@endsection