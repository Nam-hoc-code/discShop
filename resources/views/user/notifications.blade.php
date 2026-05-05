@extends('layouts.app')

@section('title', 'Thông báo của bạn')

@section('content')
<div style="max-width: 800px; margin: 0 auto; background: #121212; padding: 30px; border-radius: 12px; min-height: 80vh;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="margin: 0; font-size: 28px;">Thông báo</h1>
        <span style="color: #b3b3b3; font-size: 14px;">{{ $notifications->total() }} thông báo</span>
    </div>

    @if($notifications->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 15px;">
            @foreach($notifications as $notif)
                <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 100%; text-align: left; background: {{ $notif->is_read ? '#181818' : 'rgba(29, 185, 84, 0.05)' }}; border: 1px solid {{ $notif->is_read ? '#282828' : 'rgba(29, 185, 84, 0.2)' }}; padding: 20px; border-radius: 8px; cursor: pointer; color: white; transition: transform 0.2s, background 0.2s;" onmouseover="this.style.background='{{ $notif->is_read ? '#222' : 'rgba(29, 185, 84, 0.1)' }}'; this.style.transform='translateX(5px)'" onmouseout="this.style.background='{{ $notif->is_read ? '#181818' : 'rgba(29, 185, 84, 0.05)' }}'; this.style.transform='translateX(0)'">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                            <div style="font-size: 16px; font-weight: bold; color: {{ $notif->is_read ? '#fff' : '#1DB954' }}">
                                @if(!$notif->is_read) <span style="display: inline-block; width: 8px; height: 8px; background: #1DB954; border-radius: 50%; margin-right: 8px;"></span> @endif
                                {{ $notif->title }}
                            </div>
                            <small style="color: #777;">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                        <div style="font-size: 14px; color: #b3b3b3; line-height: 1.5;">{{ $notif->message }}</div>
                        @if($notif->link)
                            <div style="margin-top: 12px; font-size: 12px; color: #1DB954; font-weight: bold;">Nhấn để xem chi tiết →</div>
                        @endif
                    </button>
                </form>
            @endforeach
        </div>

        <div style="margin-top: 40px; display: flex; justify-content: center; gap: 10px;">
            {{ $notifications->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 100px 0;">
            <div style="font-size: 60px; color: #333; margin-bottom: 20px;"><i class="fas fa-bell-slash"></i></div>
            <h3 style="color: #b3b3b3;">Bạn chưa có thông báo nào.</h3>
            <p style="color: #777;">Hãy theo dõi các ca sĩ yêu thích để nhận tin tức mới nhất!</p>
            <a href="{{ route('home') }}" style="display: inline-block; margin-top: 20px; background: #fff; color: #000; padding: 12px 30px; border-radius: 50px; font-weight: bold;">Quay lại trang chủ</a>
        </div>
    @endif
</div>
@endsection
