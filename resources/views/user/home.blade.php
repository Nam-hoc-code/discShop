@extends('layouts.app')

@section('title', 'Trang chủ - Music Platform')

@section('content')
<div style="display:flex; gap:30px;">

    <!-- ===== DANH SÁCH BÀI HÁT (SIDEBAR) ===== -->
    <section style="width:30%;">
        <h3 style="border-bottom: 1px solid #333; padding-bottom: 10px;">Danh sách bài hát</h3>
        <div>
            @foreach ($songList as $song)
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:15px; padding: 5px; border-radius: 8px; transition: background 0.3s;" onmouseover="this.style.background='#282828'" onmouseout="this.style.background='transparent'">
                    <img src="{{ $song->cover_image ?? '/assets/images/default-cover.png' }}" alt="cover" style="width:45px;height:45px;object-fit:cover;border-radius:6px">
                    
                    <div style="flex:1">
                        <a href="{{ route('home', ['song_id' => $song->id]) }}" style="font-weight: bold; display: block;">
                            {{ $song->title }}
                        </a>
                        <a href="{{ route('artist.profile', $song->artist->id) }}" style="color: #b3b3b3; font-size: 12px;" onmouseover="this.style.color='#1DB954'" onmouseout="this.style.color='#b3b3b3'">
                            {{ $song->artist->username }}
                        </a>
                    </div>

                    <div style="display:flex; gap:15px; align-items:center;">
                        @auth
                            @php $isInPlaylist = in_array($song->id, $userPlaylistSongIds ?? []); @endphp
                            <div class="dropdown" style="position:relative; display:inline-block;">
                                <button type="button" class="dropbtn" style="border:none;background:none;cursor:pointer;font-size:20px; color: {{ $isInPlaylist ? '#1DB954' : '#b3b3b3' }}; transition: all 0.3s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'" onclick="toggleDropdown(this)">
                                    @if($isInPlaylist)
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-plus-circle"></i>
                                    @endif
                                </button>
                                <div class="dropdown-content" style="display:none; position:absolute; right:0; top: 100%; margin-top: 10px; background-color:#282828; min-width:220px; box-shadow: 0px 12px 32px 0px rgba(0,0,0,0.8); z-index:9999; border-radius:12px; padding: 12px 0; border: 1px solid #444;">
                                    <div style="padding: 5px 15px; font-size: 10px; color:#b3b3b3; text-transform:uppercase; letter-spacing: 1px; margin-bottom: 5px;">Thêm vào Playlist</div>
                                    
                                    <!-- Lựa chọn mở Modal tạo mới -->
                                    <button type="button" onclick="openCreatePlaylistModal('{{ $song->id }}')" style="width:100%; text-align:left; padding:10px 15px; background:none; border:none; color:#1DB954; cursor:pointer; font-size:13px; font-weight: bold;" onmouseover="this.style.background='#333'" onmouseout="this.style.background='none'">
                                        <i class="fas fa-plus" style="margin-right: 8px;"></i> + Tạo Playlist mới
                                    </button>
                                    
                                    <hr style="border:0; border-top:1px solid #333; margin:8px 0;">

                                    @foreach($userPlaylists as $playlist)
                                        <form action="{{ route('playlists.add_song') }}" method="POST" style="margin:0;">
                                            @csrf
                                            <input type="hidden" name="song_id" value="{{ $song->id }}">
                                            <input type="hidden" name="playlist_id" value="{{ $playlist->id }}">
                                            <button type="submit" style="width:100%; text-align:left; padding:10px 15px; background:none; border:none; color:white; cursor:pointer; font-size:13px;" onmouseover="this.style.background='#333'" onmouseout="this.style.background='none'">
                                                <i class="fas fa-list-ul" style="font-size: 10px; margin-right: 8px; color: #b3b3b3;"></i> {{ $playlist->name }}
                                            </button>
                                        </form>
                                    @endforeach
                                    
                                    @if($userPlaylists->isEmpty())
                                        <div style="padding: 10px 15px; font-size: 12px; color: #777; font-style: italic;">Chưa có danh sách nào</div>
                                    @endif

                                    <hr style="border:0; border-top:1px solid #333; margin:8px 0;">
                                    <a href="{{ route('playlists.index') }}" style="display:block; padding:8px 15px; font-size:12px; color:#b3b3b3; text-align:center;">Quản lý Playlist</a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ===== NỘI DUNG CHÍNH ===== -->
    <section style="width:70%;">
        @if($currentSong)
            <div style="background: linear-gradient(to bottom, #282828, #121212); padding: 40px; border-radius: 12px; display: flex; gap: 30px; align-items: flex-end; margin-bottom: 30px;">
                <img src="{{ $currentSong->cover_image ?? '/assets/images/default-cover.png' }}" style="width: 230px; height: 230px; box-shadow: 0 8px 24px rgba(0,0,0,0.5); border-radius: 8px; object-fit: cover;">
                <div>
                    <span style="font-size: 12px; font-weight: bold; text-transform: uppercase;">Bài hát</span>
                    <h1 style="font-size: 72px; margin: 10px 0; letter-spacing: -3px;">{{ $currentSong->title ?? 'Không rõ tiêu đề' }}</h1>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-weight: bold;">{{ isset($currentSong->artist) ? ($currentSong->artist->username ?? $currentSong->artist['username'] ?? 'Nghệ sĩ') : 'Nghệ sĩ' }}</span>
                        <span style="color: #b3b3b3;">• {{ isset($currentSong->created_at) ? (\Carbon\Carbon::parse($currentSong->created_at)->year) : '' }}</span>
                    </div>
                </div>
            </div>
        @endif

        <h2 style="margin-top: 0;">Bài hát thịnh hành</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; margin-bottom: 40px;">
            @foreach ($trendingSongs as $song)
                <div style="background: #181818; padding: 15px; border-radius: 10px; text-align: center; transition: background 0.3s;" onmouseover="this.style.background='#282828'" onmouseout="this.style.background='#181818'">
                    <a href="{{ route('home', ['song_id' => $song->id]) }}">
                        <img src="{{ $song->cover_image ?? '/assets/images/default-cover.png' }}" alt="cover" style="width:100%; aspect-ratio: 1/1; object-fit:cover; border-radius:8px; margin-bottom: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
                        <div style="font-weight: bold; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: white;">{{ $song->title }}</div>
                    </a>
                    <a href="{{ route('artist.profile', $song->artist->id) }}" style="font-size: 14px; color: #b3b3b3;" onmouseover="this.style.color='#1DB954'" onmouseout="this.style.color='#b3b3b3'">
                        {{ $song->artist->username }}
                    </a>
                </div>
            @endforeach
        </div>

        @if($events->count() > 0)
            <div style="margin-bottom: 40px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="margin: 0;">Sự kiện âm nhạc mới nhất</h2>
                    <a href="{{ route('events.index') }}" style="color: #1DB954; text-decoration: none; font-weight: bold; font-size: 14px;">Xem tất cả</a>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                    @foreach($events as $event)
                        <div style="background: #181818; border-radius: 10px; overflow: hidden; border: 1px solid #282828;">
                            <img src="{{ $event->banner_image }}" style="width: 100%; height: 140px; object-fit: cover;">
                            <div style="padding: 15px;">
                                <h4 style="margin: 0 0 10px 0; font-size: 16px;">{{ $event->name }}</h4>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 12px; color: #b3b3b3;"><i class="far fa-calendar-alt" style="margin-right: 5px;"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</span>
                                    <a href="{{ $event->buy_url }}" target="_blank" style="background: #fff; color: #000; padding: 6px 15px; border-radius: 20px; text-decoration: none; font-weight: bold; font-size: 11px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <i class="fas fa-ticket-alt" style="margin-right: 5px;"></i> Mua vé
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <h2 style="margin-top: 40px;">Nghệ sĩ phổ biến</h2>
        <div style="display: flex; gap: 30px; flex-wrap: wrap;">
            @foreach ($popularArtists as $artist)
                <div style="text-align: center; width: 140px; background: #181818; padding: 20px 15px; border-radius: 12px; transition: background 0.3s;" onmouseover="this.style.background='#282828'" onmouseout="this.style.background='#181818'">
                    <a href="{{ route('artist.profile', $artist->id) }}">
                        <div style="width: 80px; height: 80px; background: #333; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 35px; color: #555; box-shadow: 0 8px 16px rgba(0,0,0,0.3); border: 2px solid transparent; transition: border-color 0.3s;" onmouseover="this.style.borderColor='#1DB954'" onmouseout="this.style.borderColor='transparent'">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div style="font-weight: bold; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: white;">{{ $artist->username }}</div>
                    </a>
                    <div style="font-size: 12px; color: #b3b3b3; margin-bottom: 15px;">{{ $artist->songs_count }} bài hát</div>
                    
                    @auth
                        <form action="{{ route('artist.follow', $artist->id) }}" method="POST">
                            @csrf
                            @php $isFollowing = auth()->user()->followings->contains($artist->id); @endphp
                            <button type="submit" style="width: 100%; padding: 6px; border-radius: 20px; border: 1px solid {{ $isFollowing ? '#535353' : 'white' }}; background: {{ $isFollowing ? 'transparent' : 'white' }}; color: {{ $isFollowing ? 'white' : 'black' }}; font-size: 12px; font-weight: bold; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                {{ $isFollowing ? 'Đang theo dõi' : 'Theo dõi' }}
                            </button>
                        </form>
                    @endauth
                </div>
            @endforeach
        </div>
    </section>

</div>

<!-- Modal Tạo Playlist mới -->
<div id="createPlaylistModal" style="display:none; position:fixed; z-index:10000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8); backdrop-filter: blur(5px);">
    <div style="background:#282828; margin: 10% auto; padding: 30px; border-radius: 12px; width: 400px; box-shadow: 0 10px 40px rgba(0,0,0,0.5); border: 1px solid #333;">
        <h2 style="margin-top:0; margin-bottom:20px; font-size: 24px;">Tạo danh sách phát mới</h2>
        
        <form action="{{ route('playlists.store') }}" method="POST">
            @csrf
            <input type="hidden" name="auto_add_song_id" id="modal_auto_add_song_id">
            
            <div style="margin-bottom: 20px;">
                <label style="display:block; font-size:12px; font-weight:bold; color:#b3b3b3; margin-bottom:8px; text-transform:uppercase;">Tên danh sách</label>
                <input type="text" name="name" required placeholder="Tên danh sách phát của bạn" style="width:100%; padding: 12px; background:#3e3e3e; border:1px solid transparent; border-radius:4px; color:white; outline:none; font-size:14px;" onfocus="this.style.borderColor='#1DB954'">
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display:block; font-size:12px; font-weight:bold; color:#b3b3b3; margin-bottom:8px; text-transform:uppercase;">Mô tả (tùy chọn)</label>
                <textarea name="description" placeholder="Thêm mô tả cho danh sách này" style="width:100%; padding: 12px; background:#3e3e3e; border:1px solid transparent; border-radius:4px; color:white; outline:none; font-size:14px; height:80px; resize:none;" onfocus="this.style.borderColor='#1DB954'"></textarea>
            </div>

            <div style="display:flex; justify-content: flex-end; gap:15px;">
                <button type="button" onclick="closeCreatePlaylistModal()" style="background:transparent; border:none; color:white; font-weight:bold; cursor:pointer; padding: 10px 20px;">HỦY</button>
                <button type="submit" style="background:#1DB954; border:none; color:black; font-weight:bold; padding: 12px 35px; border-radius: 50px; cursor:pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">TẠO</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
window.initAppScripts = function() {
    // Xử lý Dropdown
    function toggleDropdown(btn) {
        const content = btn.nextElementSibling;
        const isVisible = content.style.display === 'block';
        
        document.querySelectorAll('.dropdown-content').forEach(el => {
            if (el !== content) el.style.display = 'none';
        });
        
        content.style.display = isVisible ? 'none' : 'block';
    }
    
    // Gán lại sự kiện cho các nút dropdown (vì innerHTML làm mất sự kiện cũ)
    document.querySelectorAll('.dropbtn').forEach(btn => {
        btn.onclick = function() { toggleDropdown(this); };
    });

    window.openCreatePlaylistModal = function(songId) {
        document.getElementById('modal_auto_add_song_id').value = songId;
        document.getElementById('createPlaylistModal').style.display = 'block';
        document.querySelectorAll('.dropdown-content').forEach(el => el.style.display = 'none');
    };

    window.closeCreatePlaylistModal = function() {
        document.getElementById('createPlaylistModal').style.display = 'none';
    };

    // Đóng dropdown & modal khi nhấn ra ngoài
    window.onclick = function(event) {
        if (!event.target.closest('.dropbtn')) {
            document.querySelectorAll('.dropdown-content').forEach(el => el.style.display = 'none');
        }

        const modal = document.getElementById('createPlaylistModal');
        if (event.target == modal) {
            closeCreatePlaylistModal();
        }
    };
};

// Chạy lần đầu khi tải trang
document.addEventListener('DOMContentLoaded', window.initAppScripts);
</script>
@endsection
