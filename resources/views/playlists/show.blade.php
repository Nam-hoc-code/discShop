@extends('layouts.app')

@section('title', $playlist->name . ' - Music Platform')

@section('content')
<div style="max-width: 1000px; margin: auto;">
    
    <!-- Header Playlist -->
    <div style="display: flex; gap: 30px; align-items: flex-end; margin-bottom: 40px; background: linear-gradient(to bottom, #333, #181818); padding: 30px; border-radius: 12px;">
        <div style="width: 200px; aspect-ratio: 1/1; background: #282828; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 80px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            🎶
        </div>
        <div style="flex: 1;">
            <div style="font-size: 0.8rem; font-weight: bold; text-transform: uppercase; margin-bottom: 8px;">DANH SÁCH PHÁT</div>
            <h1 style="margin: 0 0 15px 0; font-size: 4rem;">{{ $playlist->name }}</h1>
            <p style="color: #b3b3b3; margin: 0;">{{ $playlist->description ?? 'Không có mô tả.' }}</p>
            <div style="margin-top: 15px; font-weight: bold;">
                {{ auth()->user()->username }} • {{ $playlist->songs->count() }} bài hát
            </div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <form action="{{ route('playlists.destroy', $playlist->id) }}" method="POST" onsubmit="return confirm('Xóa danh sách phát này?')">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: rgba(255,0,0,0.2); color: #ff4d4d; border: 1px solid #ff4d4d; padding: 8px 16px; border-radius: 20px; font-weight: bold; cursor: pointer;">
                    🗑️ Xóa
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(29, 185, 84, 0.2); color: #1DB954; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Danh sách bài hát -->
    <div style="background: #181818; border-radius: 12px; overflow: hidden; border: 1px solid #333;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #333; text-align: left; color: #b3b3b3; font-size: 0.8rem; text-transform: uppercase;">
                    <th style="padding: 15px 20px;"># TIÊU ĐỀ</th>
                    <th style="padding: 15px 20px;">NGHỆ SĨ</th>
                    <th style="padding: 15px 20px;">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @foreach($playlist->songs as $index => $song)
                    <tr style="border-bottom: 1px solid #282828; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 12px 20px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <span style="color: #b3b3b3; width: 20px;">{{ $index + 1 }}</span>
                                <img src="{{ $song->cover_image ?? '/assets/images/default-cover.png' }}" alt="cover" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                                <a href="{{ route('home', ['song_id' => $song->id]) }}" style="font-weight: bold; text-decoration: none;">
                                    {{ $song->title }}
                                </a>
                            </div>
                        </td>
                        <td style="padding: 12px 20px; color: #b3b3b3;">
                            {{ $song->artist->username }}
                        </td>
                        <td style="padding: 12px 20px;">
                            <form action="{{ route('playlists.remove_song', $playlist->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="song_id" value="{{ $song->id }}">
                                <button type="submit" style="background: none; border: none; color: #b3b3b3; cursor: pointer; font-size: 1.1rem;" title="Xóa khỏi danh sách phát">
                                    <i class="fas fa-minus-circle"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($playlist->songs->isEmpty())
            <div style="text-align: center; color: #b3b3b3; padding: 80px 0;">
                <i class="fas fa-music" style="font-size: 64px; margin-bottom: 20px; opacity: 0.1;"></i>
                <p>Danh sách phát này hiện đang trống.</p>
                <a href="{{ route('home') }}" style="color: #1DB954; text-decoration: none; font-weight: bold;">Tìm bài hát để thêm</a>
            </div>
        @endif
    </div>

</div>
@endsection
