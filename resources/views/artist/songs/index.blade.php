@extends('layouts.artist')

@section('title', 'Bài hát của tôi')

@section('content')
<div class="header-section">
    <a href="{{ route('artist.dashboard') }}" class="back-btn"><i class="fas fa-chevron-left"></i></a>
    <i class="fa-solid fa-music" style="font-size: 2.2rem; color: #fff; margin-left: 10px;"></i>
    <h1>Bài hát của tôi</h1>
    <a href="{{ route('artist.songs.create') }}" class="btn-primary" style="margin-left: auto;">Thêm bài mới</a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div style="background: #181818; border-radius: 12px; overflow: hidden; border: 1px solid #333;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid #333; text-align: left; color: #b3b3b3; font-size: 0.8rem; text-transform: uppercase;">
                <th style="padding: 15px 20px;">BÌA</th>
                <th style="padding: 15px 20px;">TIÊU ĐỀ</th>
                <th style="padding: 15px 20px;">THỂ LOẠI</th>
                <th style="padding: 15px 20px;">NGHE THỬ</th>
                <th style="padding: 15px 20px;">TRẠNG THÁI</th>
                <th style="padding: 15px 20px;">HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            @forelse($songs as $song)
                <tr style="border-bottom: 1px solid #282828;">
                    <td style="padding: 15px 20px;">
                        <img src="{{ $song->cover_image }}" style="width: 50px; height: 50px; border-radius: 4px; object-fit: cover;">
                    </td>
                    <td style="padding: 15px 20px; font-weight: bold;">{{ $song->title }}</td>
                    <td style="padding: 15px 20px; color: #b3b3b3;">{{ $song->genre->name ?? 'N/A' }}</td>
                    <td style="padding: 15px 20px;">
                        <audio controls style="height: 30px; filter: invert(100%); opacity: 0.7;">
                            <source src="{{ $song->cloud_url }}" type="audio/mpeg">
                        </audio>
                    </td>
                    <td style="padding: 15px 20px;">
                        @php
                            $statusColors = [
                                'PENDING' => '#f1c40f',
                                'APPROVED' => '#1DB954',
                                'REJECTED' => '#ff4d4d'
                            ];
                        @endphp
                        <span style="color: {{ $statusColors[$song->status] ?? 'white' }}; font-weight: bold; font-size: 0.8rem;">
                            {{ $song->status }}
                        </span>
                    </td>
                    <td style="padding: 15px 20px;">
                        <form action="{{ route('artist.songs.delete', $song->id) }}" method="POST" onsubmit="return confirm('Xóa bài hát này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #ff4d4d; cursor: pointer; font-weight: bold;">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: #b3b3b3;">Bạn chưa có bài hát nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
