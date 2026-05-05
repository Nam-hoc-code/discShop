@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('styles')
    <style>
        .search-bar {
            margin-bottom: 24px;
            display: flex;
            gap: 10px;
        }

        .search-bar input {
            background-color: var(--bg-card);
            border: 1px solid #333;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            width: 300px;
        }

        .search-bar button {
            background-color: var(--accent-green);
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .user-table-container {
            background-color: rgba(24, 24, 24, 0.5);
            border-radius: 8px;
            padding: 20px;
        }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead th { color: var(--text-muted); text-transform: uppercase; font-size: 12px; padding-bottom: 12px; border-bottom: 1px solid #333; }
        td { padding: 15px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }

        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-admin { background-color: #ff5555; color: white; }
        .badge-artist { background-color: var(--accent-cyan); color: black; }
        .badge-user { background-color: #555; color: white; }

        .status-badge {
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .status-active { color: var(--accent-green); }
        .status-locked { color: #ff5555; }

        .select-role {
            background: #282828;
            color: white;
            border: 1px solid #444;
            padding: 5px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .pagination { margin-top: 20px; display: flex; gap: 10px; }
        .pagination a, .pagination span { color: white; text-decoration: none; padding: 5px 10px; background: #333; border-radius: 4px; }
        .pagination .active { background: var(--accent-green); color: black; }
    </style>
@endsection

@section('content')
    <div class="header">
        <h1>Quản lý người dùng</h1>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.users') }}" method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Tìm theo tên hoặc SĐT..." value="{{ request('search') }}">
        <button type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
        @if(request('search'))
            <a href="{{ route('admin.users') }}" style="color: var(--text-muted); display: flex; align-items: center; text-decoration: none;">Xóa lọc</a>
        @endif
    </form>

    <div class="user-table-container">
        <table>
            <thead>
                <tr>
                    <th>TÊN ĐĂNG NHẬP</th>
                    <th>SỐ ĐIỆN THOẠI</th>
                    <th>VAI TRÒ</th>
                    <th>TRẠNG THÁI</th>
                    <th>HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><b>{{ $user->username }}</b></td>
                        <td>{{ $user->phone ?? '---' }}</td>
                        <td>
                            <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="role-form">
                                @csrf
                                <select name="role" class="select-role" data-original="{{ $user->role }}" onchange="confirmRoleChange(this)">
                                    <option value="USER" {{ $user->role == 'USER' ? 'selected' : '' }}>USER</option>
                                    <option value="ARTIST" {{ $user->role == 'ARTIST' ? 'selected' : '' }}>ARTIST</option>
                                    <option value="ADMIN" {{ $user->role == 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <span class="status-badge {{ $user->status == 'ACTIVE' ? 'status-active' : 'status-locked' }}">
                                <i class="fas {{ $user->status == 'ACTIVE' ? 'fa-check-circle' : 'fa-lock' }}"></i>
                                {{ $user->status == 'ACTIVE' ? 'Đang hoạt động' : 'Bị khóa' }}
                            </span>
                        </td>
                        <td>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.status', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn {{ $user->status == 'ACTIVE' ? 'KHÓA' : 'MỞ KHÓA' }} tài khoản này?')">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; color: {{ $user->status == 'ACTIVE' ? '#ff5555' : 'var(--accent-green)' }}; cursor: pointer; font-size: 14px; font-weight: bold;">
                                        {{ $user->status == 'ACTIVE' ? 'Khóa tài khoản' : 'Mở khóa' }}
                                    </button>
                                </form>
                            @else
                                <span style="color: #555; font-size: 12px;">(Tài khoản của bạn)</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

@section('scripts')
<script>
function confirmRoleChange(selectElement) {
    const originalRole = selectElement.getAttribute('data-original');
    const newRole = selectElement.value;
    const username = selectElement.closest('tr').querySelector('b').innerText;

    if (confirm(`Bạn có chắc chắn muốn thay đổi quyền của "${username}" từ ${originalRole} sang ${newRole} không?`)) {
        selectElement.form.submit();
    } else {
        // Nếu hủy, quay lại giá trị cũ
        selectElement.value = originalRole;
    }
}
</script>
@endsection
