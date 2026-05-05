<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Music Platform')</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; margin: 0; padding: 0; background: #000; color: #fff; overflow-x: hidden; }
        a { text-decoration: none; color: inherit; }
        
        header { 
            position: fixed; top: 0; left: 0; right: 0; height: 70px;
            background: #000; border-bottom: 1px solid #222; z-index: 2000;
            display: flex; align-items: center; justify-content: space-between; padding: 0 25px;
        }
        .sidebar { 
            position: fixed; top: 71px; left: 0; bottom: 90px; width: 240px;
            background: #000; border-right: 1px solid #222; overflow-y: auto; z-index: 1000;
            padding: 20px 10px;
        }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar li { margin-bottom: 5px; }
        .sidebar li a { 
            display: flex; align-items: center; gap: 15px; padding: 12px 20px; 
            border-radius: 4px; transition: background 0.3s, color 0.3s; color: #b3b3b3; font-weight: bold;
        }
        .sidebar li a:hover { color: #fff; background: #121212; }
        .sidebar li a.active { color: #fff; background: #282828; }
        
        main { margin-left: 240px; margin-top: 71px; padding: 30px; padding-bottom: 120px; }
        
        .player-bar {
            position: fixed; bottom: 0; left: 0; right: 0; height: 90px;
            background: #000; border-top: 1px solid #222;
            padding: 0 20px; display: flex; align-items: center; justify-content: space-between;
            z-index: 3000;
        }
        .player-info { display: flex; align-items: center; gap: 15px; width: 30%; }
        .player-controls { width: 40%; text-align: center; }
        audio { width: 100%; height: 35px; border-radius: 50px; }

        .btn-logout { color: #b3b3b3; transition: color 0.3s; }
        .btn-logout:hover { color: #ff4444; }

        /* Custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
    </style>
</head>
<body>

    <header>
        <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo" style="width: 40px; height: 40px;">
            <h2 style="margin: 0; font-size: 20px; letter-spacing: -1px;">Music Platform</h2>
        </a>

        <div style="flex: 1; max-width: 400px; margin: 0 40px;">
            <form action="{{ route('search.index') }}" method="GET" style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #b3b3b3; font-size: 14px;"></i>
                <input type="text" name="q" placeholder="Bạn muốn nghe gì?" style="width: 100%; padding: 10px 15px 10px 45px; background: #121212; border: 1px solid #333; border-radius: 50px; color: white; outline: none; font-size: 14px; transition: border-color 0.3s;" onfocus="this.style.borderColor='#1DB954'">
            </form>
        </div>

        <div style="display: flex; align-items: center; gap: 25px;">
            @auth
                <!-- Notification Bell -->
                <div class="notification-dropdown" style="position: relative;">
                    <button type="button" onclick="toggleNotificationDropdown()" style="background: none; border: none; color: #b3b3b3; cursor: pointer; font-size: 20px; position: relative; transition: color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#b3b3b3'">
                        <i class="fas fa-bell"></i>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span style="position: absolute; top: -5px; right: -5px; background: #1DB954; color: white; font-size: 10px; padding: 2px 5px; border-radius: 50%; font-weight: bold;">{{ $unreadCount }}</span>
                        @endif
                    </button>
                    <div id="notif-dropdown" style="display: none; position: absolute; right: 0; top: 45px; background: #282828; width: 320px; border-radius: 8px; box-shadow: 0 16px 32px rgba(0,0,0,0.6); z-index: 5000; border: 1px solid #333;">
                        <div style="padding: 15px 20px; border-bottom: 1px solid #333; font-weight: bold; font-size: 14px;">Thông báo mới</div>
                        <div style="max-height: 400px; overflow-y: auto;">
                            @forelse($headerNotifications ?? [] as $notif)
                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="width: 100%; text-align: left; padding: 15px 20px; background: {{ $notif->is_read ? 'transparent' : 'rgba(29, 185, 84, 0.05)' }}; border: none; border-bottom: 1px solid #222; color: white; cursor: pointer; transition: background 0.3s;" onmouseover="this.style.background='#333'" onmouseout="this.style.background='{{ $notif->is_read ? 'transparent' : 'rgba(29, 185, 84, 0.05)' }}'">
                                        <div style="font-size: 13px; font-weight: bold; margin-bottom: 5px;">{{ $notif->title }}</div>
                                        <div style="font-size: 12px; color: #b3b3b3; line-height: 1.4;">{{ $notif->message }}</div>
                                        <div style="font-size: 10px; color: #777; margin-top: 8px;">{{ $notif->created_at->diffForHumans() }}</div>
                                    </button>
                                </form>
                            @empty
                                <div style="padding: 30px 20px; text-align: center; color: #b3b3b3; font-size: 13px;">Không có thông báo mới</div>
                            @endforelse
                        </div>
                        <a href="{{ route('notifications.index') }}" style="display: block; padding: 15px; text-align: center; color: #fff; font-size: 13px; font-weight: bold; background: #1DB954; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">Xem tất cả thông báo</a>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 15px;">
                    <a href="{{ route('profile.index') }}" style="display: flex; align-items: center; gap: 10px; color: white; background: #121212; padding: 6px 15px; border-radius: 50px; border: 1px solid #333; transition: all 0.3s;" onmouseover="this.style.background='#282828'; this.style.borderColor='#444'" onmouseout="this.style.background='#121212'; this.style.borderColor='#333'">
                        <i class="fas fa-user-circle" style="font-size: 20px; color: #b3b3b3;"></i>
                        <span style="font-size: 14px; font-weight: bold;">{{ Auth::user()->username }}</span>
                    </a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-logout" style="font-size: 14px; font-weight: bold; padding-left: 10px; border-left: 1px solid #333;">Đăng xuất</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" style="background: #fff; color: #000; padding: 12px 30px; border-radius: 50px; font-weight: bold; font-size: 14px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Đăng nhập</a>
            @endauth
        </div>
        <div style="clear:both;"></div>
        <script>
            function toggleNotificationDropdown() {
                var dropdown = document.getElementById('notif-dropdown');
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }
            window.onclick = function(event) {
                if (!event.target.closest('.notification-dropdown')) {
                    var dropdown = document.getElementById('notif-dropdown');
                    if (dropdown) dropdown.style.display = 'none';
                }
            }
        </script>
    </header>

    <aside class="sidebar">
        <ul>
            @auth
                @if(Auth::user()->role === 'ARTIST')
                    {{-- Artist Sidebar --}}
                    <li><a href="{{ route('artist.dashboard') }}" class="{{ request()->routeIs('artist.dashboard') ? 'active' : '' }}">🏠 Dashboard Nghệ sĩ</a></li>
                    <li><a href="{{ route('artist.songs') }}" class="{{ request()->routeIs('artist.songs*') ? 'active' : '' }}">🎤 Bài hát của tôi</a></li>
                    <li><a href="{{ route('artist.discs') }}" class="{{ request()->routeIs('artist.discs*') ? 'active' : '' }}">💿 Quản lý đĩa</a></li>
                    <li><a href="{{ route('artist.orders') }}" class="{{ request()->routeIs('artist.orders*') ? 'active' : '' }}">🧾 Đơn hàng</a></li>
                    <li><a href="{{ route('artist.songs.create') }}">➕ Thêm bài mới</a></li>
                    <hr style="border-color: #333; margin: 20px 0;">
                    <li><a href="{{ route('home') }}">🌐 Xem trang User</a></li>
                @elseif(Auth::user()->role === 'ADMIN')
                    {{-- Admin Sidebar --}}
                    <li><a href="{{ route('admin.dashboard') }}">📊 Dashboard Admin</a></li>
                    <li><a href="{{ route('admin.songs.requests') }}">🔍 Duyệt bài hát</a></li>
                    <hr style="border-color: #333; margin: 20px 0;">
                    <li><a href="{{ route('home') }}">🏠 Trang chủ User</a></li>
                @else
                    {{-- Regular User Sidebar --}}
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home" style="width: 25px;"></i> Trang chủ</a></li>
                    <li><a href="{{ route('favorites.index') }}" class="{{ request()->routeIs('favorites.*') ? 'active' : '' }}"><i class="fas fa-heart" style="width: 25px;"></i> Yêu thích</a></li>
                    <li><a href="{{ route('discs.index') }}" class="{{ request()->routeIs('discs.*') ? 'active' : '' }}"><i class="fas fa-compact-disc" style="width: 25px;"></i> Mua đĩa</a></li>
                    <li><a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'active' : '' }}"><i class="fas fa-calendar-alt" style="width: 25px;"></i> Sự kiện</a></li>
                    <li><a href="{{ route('playlists.index') }}" class="{{ request()->routeIs('playlists.*') ? 'active' : '' }}"><i class="fas fa-list" style="width: 25px;"></i> Danh sách phát</a></li>
                @endif
            @else
                {{-- Guest Sidebar --}}
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home" style="width: 25px;"></i> Trang chủ</a></li>
                <li><a href="{{ route('discs.index') }}"><i class="fas fa-compact-disc" style="width: 25px;"></i> Mua đĩa</a></li>
                <li><a href="{{ route('events.index') }}"><i class="fas fa-calendar-alt" style="width: 25px;"></i> Sự kiện</a></li>
            @endauth
        </ul>
    </aside>

    <!-- Hệ thống thông báo Toast -->
    <div id="toast-container" style="position: fixed; top: 90px; right: 25px; z-index: 9999; display: flex; flex-direction: column; gap: 10px;">
        @if(session('success'))
            <div class="toast success" style="background: #1DB954; color: black; padding: 16px 25px; border-radius: 8px; font-weight: bold; box-shadow: 0 8px 16px rgba(0,0,0,0.4); display: flex; align-items: center; gap: 12px; animation: slideIn 0.3s ease-out;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="toast error" style="background: #ff4444; color: white; padding: 16px 25px; border-radius: 8px; font-weight: bold; box-shadow: 0 8px 16px rgba(0,0,0,0.4); display: flex; align-items: center; gap: 12px; animation: slideIn 0.3s ease-out;">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>

    <script>
        // Tự động ẩn thông báo sau 3 giây
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.animation = 'fadeOut 0.5s ease-out forwards';
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            });
        });
    </script>

    <main id="app-content">
        @yield('content')
    </main>

    <div class="player-bar">
        @if(session('current_song'))
            @php 
                $s = session('current_song');
                if(is_array($s)) $s = (object)$s;
                if(isset($s->artist) && is_array($s->artist)) $s->artist = (object)$s->artist;
            @endphp
            <div class="player-info" id="current-player-info">
                <img src="{{ $s->cover_image ?? '/assets/images/default-cover.png' }}" style="width:50px;height:50px;border-radius:4px;object-fit:cover;">
                <div>
                    <div style="font-weight:bold;">{{ $s->title ?? 'Không xác định' }}</div>
                    <div style="font-size:12px;color:#b3b3b3;">{{ isset($s->artist) ? $s->artist->username : 'Nghệ sĩ' }}</div>
                </div>
            </div>
            <div class="player-controls">
                <audio id="main-audio-player" controls autoplay style="height: 35px;">
                    <source src="{{ $s->cloud_url ?? '' }}" type="audio/mpeg">
                </audio>
            </div>
            <div style="width:30%;"></div>
        @else
            <div style="width:100%; text-align:center; color:#b3b3b3;">🎧 Chọn bài hát để phát</div>
        @endif
    </div>

    <script>
        // Xử lý chuyển trang không tải lại (SPA logic)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && link.href.startsWith(window.location.origin) && !link.target && !link.hasAttribute('download')) {
                e.preventDefault();
                loadPage(link.href);
            }
        });

        // Xử lý nút Back/Forward của trình duyệt
        window.addEventListener('popstate', function() {
            loadPage(window.location.href, false);
        });

        async function loadPage(url, pushState = true) {
            try {
                const response = await fetch(url);
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Thay thế nội dung trang
                const newContent = doc.querySelector('#app-content').innerHTML;
                document.querySelector('#app-content').innerHTML = newContent;
                
                // Cập nhật tiêu đề trang
                document.title = doc.title;
                
                // Cập nhật URL
                if (pushState) {
                    history.pushState(null, '', url);
                }

                // Nếu URL có song_id, chúng ta cần cập nhật trình phát nhạc mà không khởi động lại toàn bộ
                const playerInfo = doc.querySelector('#current-player-info');
                const audioSource = doc.querySelector('#main-audio-player source');
                
                if (audioSource && url.includes('song_id=')) {
                    const currentAudio = document.getElementById('main-audio-player');
                    const newSrc = audioSource.src;
                    
                    if (currentAudio.src !== newSrc) {
                        document.getElementById('current-player-info').innerHTML = playerInfo.innerHTML;
                        currentAudio.src = newSrc;
                        currentAudio.play();
                    }
                }

                // Thực hiện lại các script cần thiết (như đóng dropdown, modal)
                if (window.initAppScripts) window.initAppScripts();
                
                // Cuộn lên đầu trang
                window.scrollTo(0, 0);

                // CẬP NHẬT TRẠNG THÁI SIDEBAR (FIX LỖI ACTIVE)
                updateSidebarActive(url);

            } catch (error) {
                console.error('Lỗi tải trang:', error);
                // Nếu lỗi AJAX, cho tải lại trang kiểu truyền thống
                if (pushState) window.location.href = url;
            }
        }

        function updateSidebarActive(currentUrl) {
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            try {
                const currentUrlObj = new URL(currentUrl);
                const currentPath = currentUrlObj.pathname;

                sidebarLinks.forEach(link => {
                    link.classList.remove('active');
                    
                    try {
                        const linkUrl = new URL(link.href);
                        const linkPath = linkUrl.pathname;

                        // Nếu là trang chủ, yêu cầu khớp chính xác
                        if (linkPath === '/home' || linkPath === '/') {
                            if (currentPath === linkPath || (currentPath === '/' && linkPath === '/home')) {
                                link.classList.add('active');
                            }
                        } 
                        // Nếu là các trang khác (ví dụ /artist/songs), khớp nếu currentPath bắt đầu bằng linkPath
                        else if (currentPath.startsWith(linkPath)) {
                            link.classList.add('active');
                        }
                    } catch(e) {}
                });
            } catch(e) {}
        }

        // Chạy lần đầu khi trang load
        document.addEventListener('DOMContentLoaded', () => {
            updateSidebarActive(window.location.href);
        });
    </script>

    @yield('scripts')
</body>
</html>
