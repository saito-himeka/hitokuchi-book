<!DOCTYPE html>
<html lang="ja">
<head>
    {{-- 本番環境かつRenderでID設定がある場合のみ実行 --}}
    @if(config('app.env') === 'production' && env('GOOGLE_ANALYTICS_ID'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS_ID') }}"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ env('GOOGLE_ANALYTICS_ID') }}');
        </script>
    @endif

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:site_name" content="ひとくちbook" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('title', 'ひとくちbook')" />
    <meta property="og:description" content="本の要約を図解で分かりやすく紹介するサイトです。" />

    <meta property="og:image" content="@yield('ogp_image', asset('img/ogp-banner.png'))" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('title', 'ひとくちbook')" />
    <meta name="twitter:image" content="@yield('ogp_image', asset('img/ogp-banner.png'))" />

    <title>@yield('title', 'ひとくちbook')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <a href="{{ route('top') }}" class="header-logo">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-icon">
                <p class="brand-name">ひとくちbook</p>
            </a>
            
            {{-- PC用ナビゲーション --}}
            <nav class="main-nav pc-only">
                <a href="{{ route('top') }}">ホーム</a>
                @auth
                    <a href="{{ route('admin.books.create') }}">新規投稿</a>
                    <a href="{{ route('admin.contacts.index') }}">お問い合わせ一覧</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                @else
                    <a href="{{ route('contact.index') }}">お問い合わせ</a>
                @endauth
            </nav>

            {{-- スマホ用ハンバーガーボタン --}}
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        {{-- app.blade.php の mobile-menu 部分を差し替え --}}
        <nav class="mobile-menu" id="mobileMenu">
            <ul>
                <li><a href="{{ route('top') }}">ホーム</a></li>
                
                {{-- 段落を分けるためのラベル --}}
                <li><p class="menu-label">ジャンルから探す</p></li>
                
                @foreach($genres as $g)
                    <li><a href="{{ route('top', ['genre_id' => $g->id]) }}" class="genre-link">{{ $g->name }}</a></li>
                @endforeach
                
                <li class="menu-divider"></li>
                
                @auth
                    <li><a href="{{ route('admin.books.create') }}">新規投稿</a></li>
                    <li><a href="{{ route('admin.contacts.index') }}">お問い合わせ一覧</a></li>
                    <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a></li>
                @else
                    <li><a href="{{ route('contact.index') }}">お問い合わせ</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <main class="main-content @auth is-admin @endauth">
        <div class="container-flex">
            {{-- 左側：メインコンテンツ --}}
            <div class="main-primary">
                @yield('content')
            </div>

            {{-- 右側：サイドバー（PC時のみ表示） --}}
            @if(!request()->is('admin/*') && !request()->is('login') && !request()->is('register'))
            <aside class="sidebar">
                <div class="sidebar-widget ad-space">
                    <p class="ad-label">ADVERTISEMENT</p>
                    <div class="ad-placeholder">ここに広告が入ります</div>
                </div>

                {{-- 最新の投稿セクション --}}
                <div class="sidebar-widget">
                    <h3 class="widget-title">最新の投稿</h3>
                    <ul class="latest-posts">
                        @foreach($latest_books as $latest)
                            <li>
                                <a href="{{ route('book.show', $latest->id) }}" class="latest-post-item">
                                    <div class="latest-post-thumb">
                                        @if($latest->image_path)
                                            {{-- 判定ロジックを追加 --}}
                                            <img src="{{ \Illuminate\Support\Str::startsWith($latest->image_path, 'http') ? $latest->image_path : asset('storage/' . $latest->image_path) }}" alt="表紙">
                                        @endif
                                    </div>
                                    <div class="latest-post-info">
                                        <p class="latest-post-title">{{ $latest->title }}</p>
                                        <span class="latest-post-date">{{ $latest->created_at->format('Y.m.d') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="sidebar-widget">
                    <h3 class="widget-title">ジャンル</h3>
                    <ul class="sidebar-list">
                        @foreach($genres as $g)
                            <li><a href="{{ route('top', ['genre_id' => $g->id]) }}">{{ $g->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="sidebar-widget">
                    <h3 class="widget-title">About Me</h3>
                    <div class="profile-area">
                        <div class="profile-header">
                            <img src="https://res.cloudinary.com/du7phxx7f/image/upload/v1770471892/%E7%84%A1%E9%A1%8C27_20260207223743_drw60q.png" alt="管理人" class="profile-thumb">
                            <p class="profile-name">うれん</p>
                        </div>
                        <p class="widget-text">
                            管理人のうれんです。 読書は好きだけど、忙しくて時間が取れない…。そんな自分自身の経験から、1枚の図解で本の核心が伝わる「ひとくちbook」を始めました。心豊かになる小説から暮らしを彩る実用書まで、私が心から「読んでよかった」と思う本だけを厳選して図解しています。
                        </p>
                    </div>
                </div>
            </aside>
            @endif
        </div>
    </main>
    
    <footer class="footer">
        <div class="footer-inner">
            <nav class="footer-nav">
                <a href="{{ route('top') }}">ホーム</a>
                <a href="{{ route('contact.index') }}">お問い合わせ</a>
                <a href="{{ route('privacy') }}">プライバシーポリシー</a> {{-- これを追加 --}}
            </nav>
            <p class="copyright">&copy; 2026 ひとくちbook</p>
        </div>
    </footer>

    {{-- ハンバーガーメニュー制御スクリプト --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburger');
            const mobileMenu = document.getElementById('mobileMenu');
            const body = document.body;

            hamburger.addEventListener('click', function() {
                hamburger.classList.toggle('active');
                mobileMenu.classList.toggle('active');
                body.classList.toggle('no-scroll');
            });

            // メニュー外クリックやリンククリックで閉じる
            const links = mobileMenu.querySelectorAll('a');
            links.forEach(link => {
                link.addEventListener('click', () => {
                    hamburger.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    body.classList.remove('no-scroll');
                });
            });
        });
    </script>
</body>
</html>