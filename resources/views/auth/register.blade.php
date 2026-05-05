@extends('layouts.auth')

@section('title', 'Đăng ký - Spotify')

@section('header_titles')
    <h1>Đăng ký để tận hưởng ngay</h1>
    <h2>Những bài hát yêu thích</h2>
@endsection

@section('content')
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="username">Tên tài khoản</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Địa chỉ Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Xác nhận mật khẩu</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn-submit">Đăng ký</button>
    </form>

    <div class="footer-links">
        <span>Bạn đã có tài khoản?</span>
        <a href="{{ route('login') }}">Đăng nhập</a>
    </div>
@endsection
