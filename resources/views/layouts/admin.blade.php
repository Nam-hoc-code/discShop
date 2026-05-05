<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Spotify Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-body: #000000;
            --bg-sidebar: #121212;
            --bg-card: #181818;
            --bg-card-hover: #282828;
            --accent-green: #1DB954;
            --accent-cyan: #00DBFF;
            --text-main: #ffffff;
            --text-muted: #b3b3b3;
        }

        body {
            margin: 0;
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Roboto', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background-color: var(--bg-sidebar);
            padding: 24px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            box-sizing: border-box;
            border-right: 1px solid #222;
        }

        .logo {
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .logo svg {
            width: 40px;
            height: 40px;
            fill: var(--accent-green);
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            flex-grow: 1;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            border-radius: 4px;
            transition: all 0.2s;
            margin-bottom: 8px;
        }

        .nav-link:hover, .nav-link.active {
            color: white;
            background-color: var(--bg-card-hover);
        }

        .logout-form {
            margin-top: auto;
        }

        .logout-btn {
            color: #ff5555;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px;
            font-weight: 500;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .logout-btn:hover {
            background-color: rgba(255, 85, 85, 0.1);
        }

        .main-content {
            margin-left: 240px;
            flex-grow: 1;
            padding: 32px;
            background: linear-gradient(to bottom, #222 0%, #000 300px);
            min-height: 100vh;
            box-sizing: border-box;
        }

        .header h1 {
            font-size: 32px;
            margin: 0 0 32px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .alert { background: rgba(29, 185, 84, 0.2); color: var(--accent-green); padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(29, 185, 84, 0.3); }
    </style>
    @yield('styles')
</head>
<body>

<div class="sidebar">
    <a href="{{ route('home') }}" class="logo">
        <svg viewBox="0 0 167.5 167.5">
            <path d="M83.7,0C37.5,0,0,37.5,0,83.7s37.5,83.7,83.7,83.7s83.7-37.5,83.7-83.7S130,0,83.7,0z M122.1,120.8 c-1.5,2.4-4.5,3.2-6.9,1.7c-19.1-11.7-43.2-14.3-71.5-7.8c-2.7,0.6-5.4-1-6.1-3.7c-0.6-2.7,1-5.4,3.7-6.1 c30.9-7,57.7-4.1,79.1,9C122.7,115.4,123.5,118.4,122.1,120.8z M132.3,98c-1.9,3-5.8,4-8.8,2.1c-21.9-13.5-55.3-17.4-81.2-9.5 c-3.3,1-6.8-0.8-7.9-4.1c-1-3.3,0.8-6.8,4.1-7.9c30-9.1,67-4.7,92,10.6C133.7,91.1,134.6,95,132.3,98z M133.3,74.5 c-26.2-15.6-69.5-17-94.7-9.4c-4,1.2-8.2-1.1-9.4-5.1c-1.2-4,1.1-8.2,5.1-9.4c30.1-9.1,78.1-7.4,109,10.9 c3.6,2.1,4.8,6.8,2.7,10.4C134,75.1,129.3,76.3,133.3,74.5z"/>
        </svg>
        <span>Spotify</span>
    </a>

    <ul class="nav-menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Trang chủ</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.songs.requests') }}" class="nav-link {{ request()->routeIs('admin.songs.requests') ? 'active' : '' }}">
                <i class="fas fa-music"></i>
                <span>Duyệt bài hát</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i>
                <span>Quản lý người dùng</span>
            </a>
        </li>
        <li>
            <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Quản lý sự kiện</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.logs') }}" class="nav-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Lịch sử hoạt động</span>
            </a>
        </li>
        
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </button>
        </form>
    </ul>
</div>

<div class="main-content">
    @yield('content')
</div>

@yield('scripts')
</body>
</html>
