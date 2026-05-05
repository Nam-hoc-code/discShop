@extends('layouts.admin')

@section('title', 'Quản lý sự kiện')

@section('styles')
    <style>
        .event-table-container {
            background-color: rgba(24, 24, 24, 0.5);
            border-radius: 8px;
            padding: 20px;
        }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead th { color: var(--text-muted); text-transform: uppercase; font-size: 12px; padding-bottom: 12px; border-bottom: 1px solid #333; }
        td { padding: 15px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); vertical-align: middle; }

        .event-info { display: flex; align-items: center; gap: 12px; }
        .event-banner { width: 80px; height: 45px; border-radius: 4px; object-fit: cover; background: #333; }
        .event-details b { display: block; font-size: 16px; }
        
        .btn-create {
            background-color: var(--accent-green);
            color: black;
            padding: 10px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 24px;
        }
        
        .btn-create:hover { transform: scale(1.05); background-color: #1ed760; }

        .actions { display: flex; gap: 15px; }
        .action-link { text-decoration: none; font-size: 14px; font-weight: 500; }
        .edit-link { color: var(--text-muted); }
        .edit-link:hover { color: white; }
        .delete-btn { background: none; border: none; color: #ff5555; cursor: pointer; padding: 0; font-family: inherit; font-size: 14px; }
        .delete-btn:hover { text-decoration: underline; }
    </style>
@endsection

@section('content')
    <div class="header">
        <h1>Quản lý sự kiện</h1>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <a href="{{ route('events.create') }}" class="btn-create">
        <i class="fas fa-plus"></i> Thêm sự kiện mới
    </a>

    <div class="event-table-container">
        @if($events->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>SỰ KIỆN</th>
                        <th>NGÀY DIỄN RA</th>
                        <th>GIÁ VÉ</th>
                        <th>HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td>
                                <div class="event-info">
                                    <img src="{{ $event->banner_image ?? 'https://via.placeholder.com/80x45' }}" class="event-banner" alt="">
                                    <div class="event-details">
                                        <b>{{ $event->name }}</b>
                                    </div>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</td>
                            <td>{{ number_format($event->price) }} VNĐ</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('events.edit', $event->id) }}" class="action-link edit-link">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Xóa sự kiện này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                <p>Chưa có sự kiện nào được tạo.</p>
            </div>
        @endif
    </div>
@endsection
