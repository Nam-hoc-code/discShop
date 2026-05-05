@extends('layouts.auth')

@section('title', 'Đăng nhập - Spotify')

@section('header_titles')
    <h1>Chào mừng quay trở lại</h1>
    <h2>Đăng nhập để tiếp tục</h2>
@endsection

@section('content')
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="username">Tên tài khoản</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required autocomplete="username">
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>

        <button type="submit" class="btn-submit">Xác nhận</button>
    </form>

    <div class="footer-links">
        <span>Bạn chưa có tài khoản?</span>
        <a href="{{ route('register') }}">Đăng ký</a>
    </div>
@endsection
