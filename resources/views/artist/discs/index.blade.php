@extends('layouts.artist')

@section('title', 'Quản lý Đĩa nhạc')

@section('content')
<div class="header-section">
    <a href="{{ route('artist.dashboard') }}" class="back-btn"><i class="fas fa-chevron-left"></i></a>
    <i class="fa-solid fa-compact-disc" style="font-size: 2.2rem; color: #fff; margin-left: 10px;"></i>
    <h1>Quản lý đĩa nhạc</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 350px; gap: 30px;">
    
    <!-- List Discs -->
    <div style="background: #181818; border-radius: 12px; overflow: hidden; border: 1px solid #333;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: solid 1px #333; text-align: left; color: #b3b3b3; font-size: 0.8rem; text-transform: uppercase;">
                    <th style="padding: 15px 20px;">ĐĨA NHẠC / ALBUM</th>
                    <th style="padding: 15px 20px;">THỂ LOẠI</th>
                    <th style="padding: 15px 20px;">GIÁ BÁN</th>
                    <th style="padding: 15px 20px;">ĐƠN HÀNG</th>
                    <th style="padding: 15px 20px;">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($discs as $disc)
                    <tr style="border-bottom: 1px solid #282828;">
                        <td style="padding: 15px 20px;">
                            <div style="font-weight: bold; font-size: 1.1rem; color: #fff; margin-bottom: 8px;">{{ $disc->title ?? 'Đĩa nhạc chưa đặt tên' }}</div>
                            <div style="font-size: 0.8rem; color: #777;">
                                @foreach($disc->songs as $song)
                                    <div>• {{ $song->title }}</div>
                                @endforeach
                            </div>
                        </td>
                        <td style="padding: 15px 20px; color: #b3b3b3;">{{ $disc->genre->name ?? 'N/A' }}</td>
                        <td style="padding: 15px 20px;">{{ number_format($disc->price) }} VNĐ</td>
                        <td style="padding: 15px 20px;">
                            @if($disc->orders_count > 0)
                                <span style="color: #1DB954;">📦 {{ $disc->orders_count }} đơn</span>
                            @else
                                <span style="color: #b3b3b3;">Chưa có đơn</span>
                            @endif
                        </td>
                        <td style="padding: 15px 20px;">
                            @if($disc->orders_count == 0)
                                <form action="{{ route('artist.discs.delete', $disc->id) }}" method="POST" onsubmit="return confirm('Xóa đĩa này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ff4d4d; cursor: pointer; font-weight: bold;">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            @else
                                <span style="color: #555;">🔒 Khóa</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #b3b3b3;">Bạn chưa đăng bán đĩa nhạc nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add New Disc Form -->
    <div style="background: #181818; padding: 25px; border-radius: 12px; border: 1px solid #333; align-self: start;">
        <h3 style="margin-top: 0; margin-bottom: 20px;">➕ Đăng bán đĩa mới</h3>
        <form action="{{ route('artist.discs.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; color:#b3b3b3; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Tên đĩa nhạc / Album</label>
                <input type="text" name="title" placeholder="VD: Album Mùa Thu Vàng" required style="width: 100%; box-sizing: border-box; background: #282828; border: 1px solid #333; color: white; padding: 12px; border-radius: 4px;">
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; color:#b3b3b3; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Thể loại chính</label>
                <select name="genre_id" required style="width: 100%; box-sizing: border-box; background: #282828; border: 1px solid #333; color: white; padding: 12px; border-radius: 4px;">
                    <option value="">-- Chọn thể loại --</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; color:#b3b3b3; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Chọn bài hát (có thể chọn nhiều)</label>
                <select id="song_selector" name="song_ids[]" multiple required style="width: 100%; height: 150px; background: #282828; border: 1px solid #333; color: white; padding: 10px; border-radius: 4px;" onchange="updateLeadSongOptions()">
                    @foreach($songs as $song)
                        <option value="{{ $song->id }}">{{ $song->title }}</option>
                    @endforeach
                </select>
                <small style="color: #777; display: block; margin-top: 8px;">* Giữ phím Ctrl (hoặc Cmd) để chọn nhiều bài. Chỉ bài đã được duyệt.</small>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display:block; margin-bottom:8px; color:#1DB954; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; font-weight: bold;">Bài hát chủ đạo (Vị trí số 1)</label>
                <select id="lead_song_selector" name="first_song_id" required style="width: 100%; box-sizing: border-box; background: #282828; border: 1px solid #1DB954; color: white; padding: 12px; border-radius: 4px;">
                    <option value="">-- Vui lòng chọn bài hát trước --</option>
                </select>
                <small style="color: #777; display: block; margin-top: 8px;">Bài này sẽ được hiển thị đầu tiên và dùng làm tên đại diện cho đĩa.</small>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display:block; margin-bottom:8px; color:#b3b3b3; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Giá bán (VNĐ)</label>
                <input type="number" name="price" min="1000" placeholder="VD: 50000" required style="width: 100%; box-sizing: border-box; background: #282828; border: 1px solid #333; color: white; padding: 12px; border-radius: 4px;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%;">💿 ĐĂNG BÁN NGAY</button>
        </form>
    </div>

</div>

<script>
function updateLeadSongOptions() {
    const songSelector = document.getElementById('song_selector');
    const leadSelector = document.getElementById('lead_song_selector');
    const selectedOptions = Array.from(songSelector.selectedOptions);
    
    // Lưu lại giá trị cũ nếu có
    const previousValue = leadSelector.value;
    
    // Xóa các option cũ
    leadSelector.innerHTML = '<option value="">-- Chọn bài hát đầu tiên --</option>';
    
    selectedOptions.forEach(opt => {
        const newOpt = document.createElement('option');
        newOpt.value = opt.value;
        newOpt.text = opt.text;
        if (opt.value === previousValue) {
            newOpt.selected = true;
        }
        leadSelector.appendChild(newOpt);
    });

    if (selectedOptions.length === 1) {
        leadSelector.value = selectedOptions[0].value;
    }
}
</script>
@endsection
