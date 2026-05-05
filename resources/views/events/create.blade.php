@extends('layouts.app')

@section('title', 'Thêm sự kiện - Admin')

@section('content')
<div style="max-width: 600px; margin: auto;">
    <h2 style="margin-bottom: 30px;">➕ Thêm sự kiện mới</h2>

    <div style="background: #181818; padding: 30px; border-radius: 12px; border: 1px solid #333;">
        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #b3b3b3;">TÊN SỰ KIỆN</label>
                <input type="text" name="name" required style="width: 100%; padding: 12px; background: #282828; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #b3b3b3;">NGÀY DIỄN RA</label>
                <input type="date" name="event_date" required style="width: 100%; padding: 12px; background: #282828; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #b3b3b3;">GIÁ VÉ (VNĐ)</label>
                <input type="number" name="price" min="0" value="0" style="width: 100%; padding: 12px; background: #282828; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #b3b3b3;">LINK MUA VÉ</label>
                <input type="url" name="buy_url" required placeholder="https://..." style="width: 100%; padding: 12px; background: #282828; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; color: #b3b3b3;">ẢNH BANNER (IMAGE)</label>
                <input type="file" name="banner" accept="image/*" required style="width: 100%; padding: 10px; background: #282828; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" style="flex: 1; background: #1DB954; color: black; border: none; padding: 16px; border-radius: 50px; font-weight: bold; cursor: pointer; font-size: 1.1rem;">
                    THÊM SỰ KIỆN
                </button>
                <a href="{{ route('events.index') }}" style="flex: 1; display: flex; align-items: center; justify-content: center; background: #333; color: white; text-decoration: none; border-radius: 50px; font-weight: bold;">
                    HỦY BỎ
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
