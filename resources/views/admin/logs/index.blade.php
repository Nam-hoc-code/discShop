@extends('layouts.admin')

@section('title', 'Lịch sử hoạt động')

@section('styles')
    <style>
        .log-table-container {
            background-color: rgba(24, 24, 24, 0.5);
            border-radius: 8px;
            padding: 20px;
        }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead th { color: var(--text-muted); text-transform: uppercase; font-size: 11px; padding-bottom: 12px; border-bottom: 1px solid #333; }
        td { padding: 12px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: 14px; }

        .action-tag {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .tag-duyet { background: var(--accent-green); color: black; }
        .tag-tu-choi { background: #ff5555; color: white; }
        .tag-khoa { background: #000; color: #ff5555; border: 1px solid #ff5555; }
        .tag-mo-khoa { background: #000; color: var(--accent-green); border: 1px solid var(--accent-green); }
        .tag-sua-quyen { background: var(--accent-cyan); color: black; }

        .timestamp { color: var(--text-muted); font-size: 12px; }
    </style>
@endsection

@section('content')
    <div class="header">
        <h1>Lịch sử hoạt động</h1>
    </div>

    <div class="log-table-container">
        <table>
            <thead>
                <tr>
                    <th>THỜI GIAN</th>
                    <th>ADMIN</th>
                    <th>HÀNH ĐỘNG</th>
                    <th>ĐỐI TƯỢNG</th>
                    <th>CHI TIẾT</th>
                    <th>IP</th>
                    <th>HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="timestamp">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td><b>{{ $log->user->username }}</b></td>
                        <td>
                            @php
                                $tagClass = 'tag-default';
                                if($log->action == 'DUYỆT') $tagClass = 'tag-duyet';
                                if($log->action == 'TỪ CHỐI') $tagClass = 'tag-tu-choi';
                                if($log->action == 'KHÓA') $tagClass = 'tag-khoa';
                                if($log->action == 'MỞ KHÓA') $tagClass = 'tag-mo-khoa';
                                if($log->action == 'SỬA QUYỀN') $tagClass = 'tag-sua-quyen';
                                if($log->action == 'HOÀN TÁC') $tagClass = 'tag-mo-khoa';
                            @endphp
                            <span class="action-tag {{ $tagClass }}">{{ $log->action }}</span>
                        </td>
                        <td><small style="color: var(--accent-cyan)">{{ $log->target_type }} #{{ $log->target_id }}</small></td>
                        <td>{{ $log->description }}</td>
                        <td style="color: #777; font-size: 12px;">{{ $log->ip_address }}</td>
                        <td>
                            @if($log->data && $log->action !== 'HOÀN TÁC')
                                <form action="{{ route('admin.logs.undo', $log->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn HOÀN TÁC hành động này không?')">
                                    @csrf
                                    <button type="submit" style="background: #333; color: white; border: 1px solid #444; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 11px;">
                                        <i class="fas fa-undo"></i> Hoàn tác
                                    </button>
                                </form>
                            @else
                                <span style="color: #444; font-size: 11px;">---</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">Chưa có lịch sử hoạt động nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
