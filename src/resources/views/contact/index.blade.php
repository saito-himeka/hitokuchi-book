@extends('layouts.app')

@section('content')
<div class="contact-container">
    <h2 class="section-title">お問い合わせ</h2>
    <p class="contact-intro">サイトへのご意見、ご感想、お仕事の依頼などはこちらからお願いします。</p>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
        @csrf
        <div class="form-group">
            <label>お名前</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="form-input">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>お問い合わせ内容</label>
            <textarea name="message" rows="5" required class="form-input">{{ old('message') }}</textarea>
            @error('message') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="submit-button">送信する</button>
    </form>
</div>
@endsection