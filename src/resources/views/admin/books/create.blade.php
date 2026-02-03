@extends('layouts.app')

@section('title', '【管理者】新規要約投稿')

@section('content')
<div class="auth-content">
    <h1 class="page-title">新しい本の要約を投稿</h1>

    <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>本の表紙画像（トップページ用）</label>
            <input type="file" name="image" accept="image/*">
        </div>

        <div class="form-group">
            <label>本のタイトル</label>
            <input type="text" name="title" placeholder="例：エッセンシャル思考" required>
        </div>

        <div class="form-group">
            <label>著者名</label>
            <input type="text" name="author" placeholder="例：グレッグ・マキューン" required>
        </div>

        <div class="form-group">
            <label>ジャンル</label>
            {{-- name属性が "genre_id" になっていることが大事です --}}
            <select name="genre_id" required class="form-control">
                <option value="">選択してください</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>要約本文</label>
            <textarea name="summary" rows="10" placeholder="ここに要約を詳しく記入してください" required></textarea>
        </div>

        <div class="form-group">
            <label>アフィリエイトURL</label>
            <input type="url" name="affiliate_url" placeholder="https://amazon.co.jp/...">
        </div>
        
        <div class="form-group">
            <label>図解画像（詳細ページ用）</label>
            <input type="file" name="diagram" accept="image/*">
        </div>

        <button type="submit" class="read-more">この内容で投稿する</button>
    </form>
    
    <div style="margin-top: 20px; text-align: center;">
        <a href="{{ route('top') }}">トップへ戻る</a>
    </div>
</div>
@endsection