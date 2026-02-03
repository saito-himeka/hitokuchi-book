@extends('layouts.app')

@section('title', $book->title . ' - 要約と図解')

@section('content')
<article class="post-detail">
    <header class="post-header" >
        <div class="post-header-flex">
            @if($book->image_path)
                <div class="book-cover-small">
                    <img src="{{ asset('storage/' . $book->image_path) }}" alt="表紙" style="width: 120px;">
                </div>
            @endif

            <div class="post-meta">
                <span class="category">{{ $book->genre->name }}</span>
                <h1 class="post-title">{{ $book->title }}</h1>
                <p class="post-author">著者：{{ $book->author }}</p>
            </div>
        </div>
        
        <div class="summary-box">
            <h3>この本のポイント</h3>
            <p>{!! nl2br(e($book->summary)) !!}</p>
        </div>
    </header>

    <section class="visual-summary">
        <h2>1枚でわかる！本の全体像</h2>
        <div class="infographic-container">
            @if($book->diagram_path)
                {{-- zoomable-image クラスと ID を追加 --}}
                <img src="{{ asset('storage/' . $book->diagram_path) }}" alt="図解" class="zoomable-image" id="diagramImage">
                <p class="zoom-hint">（クリックで拡大します）</p>
            @else
                <p>図解画像は準備中です。</p>
            @endif
        </div>
    </section>

    @if($book->affiliate_url)
    <section class="affiliate-section">
        <div class="cta-box">
            <p class="cta-text">この本の詳細やレビューをチェックする</p>
            <a href="{{ $book->affiliate_url }}" target="_blank" rel="noopener noreferrer" class="amazon-button">
                <span class="amazon-icon">am</span> Amazonで詳細を見る
            </a>
        </div>
    </section>
    @endif
</article>

@auth
<div class="admin-actions">
    <a href="{{ route('admin.books.edit', $book->id) }}" class="btn-edit">
        <i class="fas fa-edit"></i> 編集する
    </a>
    <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-delete" onclick="return confirm('本当に削除しますか？')">
            削除する
        </button>
    </form>
</div>
@endauth

{{-- ★拡大表示用のオーバーレイ（背景） --}}
<div id="imageOverlay" class="image-overlay">
    <span class="close-overlay">&times;</span>
    <img id="enlargedImage" src="" alt="拡大図">
</div>

{{-- ★拡大機能を動かすJavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('imageOverlay');
    const mainImg = document.getElementById('diagramImage');
    const enlargedImg = document.getElementById('enlargedImage');

    if (mainImg) {
        mainImg.onclick = function() {
            overlay.style.display = "flex"; // flexに変更して中央寄せ
            enlargedImg.src = this.src;
            document.body.style.overflow = 'hidden'; // 背後のスクロールを止める
        }
    }

    overlay.onclick = function() {
        overlay.style.display = "none";
        document.body.style.overflow = 'auto'; // スクロール再開
    }
});
</script>
@endsection