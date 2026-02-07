@extends('layouts.app')

@section('content')
<div class="top-container">
    <section class="hero">
        <h2 class="hero-copy">暮らしを彩る、ひとくちサイズの出会い。</h2>
    </section>

    <section class="search-section">
        <form action="{{ route('top') }}" method="GET" class="search-form">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="本のタイトル、著者名で探す" class="search-input">
            <button type="submit" class="search-button">検索</button>
        </form>
    </section>

    <section class="genre-section">
        <h3 class="section-title">ジャンルから探す</h3>
        <div class="genre-list">
            {{-- 絞り込み解除（すべて表示） --}}
            <a href="{{ route('top') }}" class="genre-item {{ !request('genre_id') ? 'active' : '' }}">すべて</a>

            {{-- DBから取得したジャンルを表示（BookControllerから渡す必要があります） --}}
            @foreach($genres as $g)
                <a href="{{ route('top', ['genre_id' => $g->id]) }}" 
                class="genre-item {{ request('genre_id') == $g->id ? 'active' : '' }}">
                    {{ $g->name }}
                </a>
            @endforeach
        </div>
    </section>
    <section class="recommend-section">
        <h3 class="section-title">あなたにおすすめの一冊</h3>
        
        <div class="product-grid">
            {{-- @foreach を @forelse に変更 --}}
            @forelse($books as $book)
                <div class="product-card">
                    <div class="product-image">
                        <span class="badge">{{ $book->genre->name }}</span>
                        @if($book->image_path)
                            {{-- assetの代わりに Storage::url を使うのがLaravelの推奨です --}}
                            <img src="{{ \Storage::url($book->image_path) }}" alt="{{ $book->title }}">
                        @else
                            <img src="https://via.placeholder.com/150x200?text=No+Image" alt="no image">
                        @endif
                    </div>

                    <div class="product-info">
                        <p class="product-title">{{ $book->title }}</p>
                        <p class="product-author">著者：{{ $book->author }}</p>
                        <a href="{{ route('book.show', ['id' => $book->id]) }}" class="read-more">
                            要約と図解を見る
                        </a>
                    </div>
                </div>
            @empty
                {{-- ★ここが重要！検索結果が0件のときに表示されるエリア --}}
                <div class="no-results">
                    <p class="no-results-text">お探しの条件に合う本は、まだ登録されていないようです。</p>
                    <a href="{{ route('top') }}" class="back-to-top">すべての本を表示する</a>
                </div>
            @endforelse
        </div>

        {{-- ★ ページネーションリンクを追加 --}}
        <div class="pagination-wrapper">
            {{ $books->links() }}
        </div>
    </section>
</div>
@endsection