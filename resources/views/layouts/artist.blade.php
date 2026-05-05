<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Artist Dashboard') - Music Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root { 
            --bg-black: #000000; 
            --sidebar-bg: #121212; 
            --card-bg: #181818; 
            --text-main: #ffffff; 
            --text-sub: #b3b3b3; 
            --spotify-green: #1DB954; 
            --nav-hover: #282828;
            --logout-red: #f15555;
        }

        body { 
            font-family: 'Segoe UI', Roboto, sans-serif; 
            margin: 0; 
            display: flex; 
            background-color: var(--bg-black); 
            color: var(--text-main); 
        }

        .sidebar { 
            width: 280px; 
            height: 100vh; 
            background: var(--sidebar-bg); 
            position: fixed; 
            padding: 32px 16px; 
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            border-right: 1px solid #222;
        }

        .logo-container {
            display: flex;
            align-items: center;
            padding: 0 16px 40px 16px;
            gap: 12px;
            text-decoration: none;
            color: white;
        }
        
        .logo-container span { font-size: 1.6rem; font-weight: bold; letter-spacing: -1px; }

        .nav-group { flex-grow: 1; }

        .nav-link { 
            display: flex; 
            align-items: center; 
            color: var(--text-sub); 
            text-decoration: none; 
            padding: 14px 16px; 
            border-radius: 8px; 
            font-weight: bold; 
            font-size: 15px;
            transition: 0.2s;
            margin-bottom: 8px;
        }

        .nav-link:hover { color: #fff; background-color: var(--nav-hover); }
        .nav-link.active { background-color: var(--nav-hover); color: #fff; }
        
        .nav-link i { 
            margin-right: 18px; 
            font-size: 22px; 
            width: 28px; 
            text-align: center; 
            color: var(--text-sub);
        }
        .nav-link.active i { color: #fff; }
        .nav-link:hover i { color: #fff; }

        .nav-link.logout { color: var(--logout-red); margin-top: auto; border: none; background: none; width: 100%; cursor: pointer; text-align: left; }
        .nav-link.logout:hover { background-color: rgba(241, 85, 85, 0.1); }

        .main { margin-left: 280px; padding: 40px; width: 100%; box-sizing: border-box; min-height: 100vh; }
        
        .header-section { display: flex; align-items: center; gap: 20px; margin-bottom: 40px; }
        h1 { font-size: 2.2rem; margin: 0; font-weight: 800; }

        .back-btn { 
            color: var(--text-sub); 
            font-size: 1.2rem; 
            transition: 0.2s; 
            padding: 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .back-btn:hover { color: #fff; background: var(--nav-hover); }

        /* Form & Table styles for consistency */
        input, select, textarea {
            background: #242424;
            border: 1px solid #333;
            color: white;
            padding: 14px 18px;
            border-radius: 8px;
            outline: none;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
        }
        input:focus { border-color: var(--spotify-green); background: #2a2a2a; }

        label { 
            display: block; 
            margin-bottom: 12px; 
            color: var(--text-sub); 
            font-size: 0.75rem; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
        }

        .btn-primary {
            background: var(--spotify-green);
            color: black;
            padding: 16px 32px;
            border-radius: 50px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.2s;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
            text-align: center;
        }
        .btn-primary:hover { transform: scale(1.04); background: #1ed760; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: rgba(29, 185, 84, 0.1); color: var(--spotify-green); border: 1px solid rgba(29, 185, 84, 0.2); }
        .alert-error { background: rgba(241, 85, 85, 0.1); color: var(--logout-red); border: 1px solid rgba(241, 85, 85, 0.2); }
    </style>
    @yield('styles')
</head>
<body>

<div class="sidebar">
    <a href="{{ route('home') }}" class="logo-container">
        <svg width="36" height="36" viewBox="0 0 167.5 167.5" fill="#1DB954">
            <path d="M83.7 0C37.5 0 0 37.5 0 83.7c0 46.3 37.5 83.7 83.7 83.7 46.3 0 83.7-37.5 83.7-83.7C167.5 37.5 130 0 83.7 0zm38.4 120.7c-1.5 2.5-4.8 3.3-7.3 1.8-19.1-11.7-43.2-14.3-71.5-7.8-2.9.7-5.7-1.1-6.4-4-.7-2.9 1.1-5.7 4-6.4 31.1-7.1 57.8-4.1 79.4 9.1 2.5 1.5 3.3 4.8 1.8 7.3zm10.2-22.8c-1.9 3.1-5.9 4.1-9 2.2-21.9-13.5-55.2-17.4-81.1-9.5-3.5 1.1-7.1-1-8.2-4.5-1.1-3.5 1-7.1 4.5-8.2 29.5-8.9 66.3-4.6 91.5 10.8 3.2 2 4.1 6.1 2.3 9.2zm.9-23.9C105.3 57.5 61.2 56 35.8 63.7c-4.3 1.3-8.8-1.2-10.1-5.5-1.3-4.3 1.2-8.8 5.5-10.1 30.1-9.1 79-7.4 109.2 10.5 3.9 2.3 5.2 7.3 2.9 11.2s-7.2 5.2-11.1 2.9z"/>
        </svg>
        <span>Spotify</span>
    </a>

    <div class="nav-group">
        <a href="{{ route('artist.dashboard') }}" class="nav-link {{ request()->routeIs('artist.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <a href="{{ route('artist.songs') }}" class="nav-link {{ request()->routeIs('artist.songs*') ? 'active' : '' }}"><i class="fa-solid fa-music"></i> Bài hát của tôi</a>
        <a href="{{ route('artist.discs') }}" class="nav-link {{ request()->routeIs('artist.discs*') ? 'active' : '' }}"><i class="fa-solid fa-compact-disc"></i> Quản lý đĩa</a>
        <a href="{{ route('artist.orders') }}" class="nav-link {{ request()->routeIs('artist.orders*') ? 'active' : '' }}"><i class="fa-solid fa-receipt"></i> Đơn hàng</a>
        <a href="{{ route('artist.songs.create') }}" class="nav-link {{ request()->routeIs('artist.songs.create') ? 'active' : '' }}"><i class="fa-solid fa-circle-plus"></i> Thêm bài mới</a>
        
        <hr style="border-color: #282828; margin: 20px 0;">
        <a href="{{ route('home') }}" class="nav-link"><i class="fa-solid fa-globe"></i> Xem trang User</a>

        <form action="{{ route('logout') }}" method="POST" style="margin-top: auto;">
            @csrf
            <button type="submit" class="nav-link logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</button>
        </form>
    </div>
</div>

<div class="main">
    @yield('content')
</div>

@yield('scripts')
</body>
</html>
