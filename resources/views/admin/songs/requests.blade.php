@extends('layouts.admin')

@section('title', 'Duyệt bài hát')

@section('styles')
    <style>
        .song-list-container {
            background-color: rgba(24, 24, 24, 0.5);
            border-radius: 8px;
            padding: 20px;
        }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead th { color: var(--text-muted); text-transform: uppercase; font-size: 12px; padding-bottom: 12px; border-bottom: 1px solid #333; }
        td { padding: 12px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }

        .song-info { display: flex; align-items: center; gap: 12px; }
        .song-img { width: 40px; height: 40px; border-radius: 4px; object-fit: cover; background: #333; }
        .song-details b { display: block; font-size: 16px; }
        .song-details span { font-size: 14px; color: var(--text-muted); }

        .actions { display: flex; gap: 10px; }
        .btn-action {
            padding: 6px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: transform 0.1s;
        }

        .btn-approve { background-color: var(--accent-green); color: black; }
        .btn-reject { background: none; border: 1px solid var(--text-muted); color: white; }
        .btn-action:hover { transform: scale(1.05); }

        .empty-state { text-align: center; padding: 40px; color: var(--text-muted); }
    </style>
@endsection

@section('content')
    <div class="header">
        <h1>Danh sách chờ duyệt</h1>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="song-list-container">
        @if($requests->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th># TIÊU ĐỀ</th>
                        <th>NGÀY GỬI</th>
                        <th>HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $song)
                        <tr>
                            <td>
                                <div class="song-info">
                                    <img src="{{ $song->cover_image ?? 'https://via.placeholder.com/40' }}" class="song-img" alt="">
                                    <div class="song-details">
                                        <b>{{ $song->title }}</b>
                                        <span>{{ $song->artist->username }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $song->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="actions">
                                    <form action="{{ route('admin.songs.approve', $song->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-action btn-approve">Duyệt</button>
                                    </form>
                                    <form action="{{ route('admin.songs.reject', $song->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-action btn-reject">Từ chối</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 16px; color: var(--accent-green);"></i>
                <p>Không có bài hát nào đang chờ duyệt!</p>
            </div>
        @endif
    </div>
@endsection
