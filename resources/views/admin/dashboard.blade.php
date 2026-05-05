@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('styles')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 48px;
        }

        .stat-card {
            background-color: var(--bg-card);
            padding: 24px;
            border-radius: 8px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid #222;
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover { background-color: var(--bg-card-hover); }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .icon-users { background-color: rgba(0, 219, 255, 0.1); color: var(--accent-cyan); }
        .icon-songs { background-color: rgba(29, 185, 84, 0.1); color: var(--accent-green); }
        .icon-pending { background-color: rgba(255, 165, 0, 0.1); color: #ffa500; }

        .stat-info h3 {
            font-size: 14px;
            color: var(--text-muted);
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 1px;
        }

        .stat-value { font-size: 28px; font-weight: bold; }
    </style>
@endsection

@section('content')
    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-users">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>Tổng người dùng</h3>
                <div class="stat-value">{{ $totalUsers }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-songs">
                <i class="fas fa-music"></i>
            </div>
            <div class="stat-info">
                <h3>Tổng bài hát</h3>
                <div class="stat-value">{{ $totalSongs }}</div>
            </div>
        </div>

        <a href="{{ route('admin.songs.requests') }}" class="stat-card">
            <div class="stat-icon icon-pending">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-info">
                <h3>Bài chờ duyệt</h3>
                <div class="stat-value">{{ $pendingSongs }}</div>
            </div>
        </a>
    </div>
@endsection
