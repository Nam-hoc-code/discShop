@extends('layouts.app')

@section('title', 'Danh sách phát - Music Platform')

@section('content')
<div style="max-width: 1000px; margin: auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="margin: 0;">📚 Danh sách phát của bạn</h2>
        <button onclick="document.getElementById('createPlaylistModal').style.display='flex'" style="background: #1DB954; color: black; padding: 12px 24px; border-radius: 50px; font-weight: bold; border: none; cursor: pointer;">
            ➕ Tạo danh sách phát mới
        </a>
    </div>

    @if(session('success'))
        <div style="background: rgba(29, 185, 84, 0.2); color: #1DB954; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 24px;">
        @foreach($playlists as $playlist)
            <div style="background: #181818; padding: 20px; border-radius: 12px; transition: background 0.3s; border: 1px solid #333;" onmouseover="this.style.background='#282828'" onmouseout="this.style.background='#181818'">
                <a href="{{ route('playlists.show', $playlist->id) }}" style="text-decoration: none;">
                    <div style="width: 100%; aspect-ratio: 1/1; background: #333; border-radius: 8px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; font-size: 40px; box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
                        🎶
                    </div>
                    <h3 style="margin: 0 0 5px 0; font-size: 1.1rem; color: white;">{{ $playlist->name }}</h3>
                    <p style="color: #b3b3b3; margin: 0; font-size: 0.9rem;">{{ $playlist->songs_count }} bài hát</p>
                </a>
            </div>
        @endforeach
    </div>

    @if($playlists->isEmpty())
        <div style="text-align: center; color: #b3b3b3; padding: 80px 0; background: #181818; border-radius: 12px; border: 1px solid #333;">
            <i class="fas fa-list-ul" style="font-size: 64px; margin-bottom: 20px; opacity: 0.1;"></i>
            <p>Bạn chưa có danh sách phát nào.</p>
            <button onclick="document.getElementById('createPlaylistModal').style.display='flex'" style="background: none; border: none; color: #1DB954; font-weight: bold; cursor: pointer; font-size: 1rem;">Tạo ngay</button>
        </div>
    @endif

</div>

<!-- Modal tạo Playlist -->
<div id="createPlaylistModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #282828; padding: 40px; border-radius: 12px; width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <h3 style="margin-top:0; margin-bottom:20px;">Tạo danh sách phát mới</h3>
        <form action="{{ route('playlists.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; color:#b3b3b3; font-size: 0.9rem;">TÊN DANH SÁCH</label>
                <input type="text" name="name" required style="width:100%; padding: 12px; background:#181818; border:1px solid #444; border-radius:6px; color:white; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 30px;">
                <label style="display:block; margin-bottom:8px; color:#b3b3b3; font-size: 0.9rem;">MÔ TẢ (TÙY CHỌN)</label>
                <textarea name="description" rows="3" style="width:100%; padding: 12px; background:#181818; border:1px solid #444; border-radius:6px; color:white; box-sizing: border-box; resize:none;"></textarea>
            </div>
            <div style="display:flex; gap:15px;">
                <button type="submit" style="flex:1; background:#1DB954; color:black; border:none; padding:12px; border-radius:50px; font-weight:bold; cursor:pointer;">TẠO</button>
                <button type="button" onclick="document.getElementById('createPlaylistModal').style.display='none'" style="flex:1; background:#333; color:white; border:none; padding:12px; border-radius:50px; font-weight:bold; cursor:pointer;">HỦY</button>
            </div>
        </form>
    </div>
</div>

@endsection
