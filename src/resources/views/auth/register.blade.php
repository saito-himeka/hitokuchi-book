@extends('layouts.app')

@section('title', '管理者登録')

@section('content')
<div class="auth-content">
    <h1 class="page-title">管理者アカウント作成</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label>お名前</label>
            <input type="text" name="name" required autofocus>
        </div>

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>パスワード（確認用）</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit" class="read-more">アカウントを作成する</button>
    </form>
</div>
@endsection