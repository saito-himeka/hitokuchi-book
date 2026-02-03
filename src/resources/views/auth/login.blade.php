@extends('layouts.app')

@section('title', 'ログイン - ひとくちbook')

@section('content')
<div class="auth-content">
    <h1 class="page-title">ログイン</h1>
    <form method="POST" action="{{ route('login') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">ログイン</button>
    </form>

</div>
@endsection