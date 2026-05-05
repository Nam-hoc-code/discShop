@extends('layouts.artist')

@section('title', 'Dashboard')

@section('content')
    <div class="header-section">
        <h1>Chào mừng nghệ sĩ {{ auth()->user()->username }} quay trở lại</h1>
    </div>
    
    <div style="display: flex; gap: 24px; margin-bottom: 40px;">
        <a href="{{ route('artist.songs') }}" style="background: var(--card-bg); padding: 24px; border-radius: 8px; flex: 1; transition: background 0.3s; cursor: pointer; text-decoration: none; color: inherit;">
            <h3 style="margin: 0; color: var(--text-sub); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Tổng bài hát</h3>
            <p style="font-size: 2.5rem; font-weight: bold; margin: 12px 0 0; color: var(--spotify-green);">{{ $totalSongs }}</p>
        </a>
        <div style="background: var(--card-bg); padding: 24px; border-radius: 8px; flex: 1;">
            <h3 style="margin: 0; color: var(--text-sub); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Đang chờ duyệt</h3>
            <p style="font-size: 2.5rem; font-weight: bold; margin: 12px 0 0; color: var(--spotify-green);">{{ $pendingSongs }}</p>
        </div>
        <a href="{{ route('artist.orders') }}" style="background: var(--card-bg); padding: 24px; border-radius: 8px; flex: 1; transition: background 0.3s; cursor: pointer; text-decoration: none; color: inherit;">
            <h3 style="margin: 0; color: var(--text-sub); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Tổng đơn hàng</h3>
            <p style="font-size: 2.5rem; font-weight: bold; margin: 12px 0 0; color: var(--spotify-green);">{{ $totalOrders }}</p>
        </a>
    </div>

    <div style="background: linear-gradient(to bottom, #282828, #181818); padding: 40px; border-radius: 8px; border: 1px solid #333;">
        <h2>Bạn có bản nhạc mới?</h2>
        <p style="color: var(--text-sub);">Chia sẻ tác phẩm của bạn với thế giới ngay hôm nay.</p>
        <a href="{{ route('artist.songs.create') }}" class="btn-primary" style="margin-top: 20px;">Upload bài hát ngay</a>
    </div>
@endsection
