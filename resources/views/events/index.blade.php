@extends('layouts.app')

@section('title', 'Sự kiện âm nhạc - Music Platform')

@section('content')
<div style="max-width: 1000px; margin: auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="margin: 0;">🎵 Sự kiện âm nhạc mới nhất</h2>
        @if(auth()->user()->role === 'ADMIN')
            <a href="{{ route('events.create') }}" style="background: #1DB954; color: black; padding: 10px 20px; border-radius: 50px; font-weight: bold; text-decoration: none;">
                ➕ Thêm sự kiện
            </a>
        @endif
    </div>

    @if(session('success'))
        <div style="background: rgba(29, 185, 84, 0.2); color: #1DB954; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
        @foreach($events as $event)
            <div style="background: #181818; border-radius: 12px; overflow: hidden; border: 1px solid #333; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                @if($event->banner_image)
                    <img src="{{ $event->banner_image }}" alt="banner" style="width: 100%; height: 200px; object-fit: cover;">
                @else
                    <div style="width: 100%; height: 200px; background: #333; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-alt" style="font-size: 48px; color: #555;"></i>
                    </div>
                @endif
                
                <div style="padding: 20px;">
                    <h3 style="margin: 0 0 10px 0; font-size: 1.3rem; height: 3.2rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{ $event->name }}
                    </h3>
                    
                    <div style="display: flex; align-items: center; gap: 10px; color: #b3b3b3; margin-bottom: 15px; font-size: 0.9rem;">
                        <i class="far fa-calendar-alt"></i>
                        <span>{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <span style="font-weight: bold; color: #1DB954; font-size: 1.1rem;">{{ number_format($event->price) }} VNĐ</span>
                        <a href="{{ $event->buy_url }}" target="_blank" style="background: #fff; color: #000; padding: 8px 16px; border-radius: 20px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">
                            🎟️ Mua vé
                        </a>
                    </div>

                    @if(auth()->user()->role === 'ADMIN')
                        <div style="display: flex; gap: 15px; border-top: 1px solid #333; padding-top: 15px;">
                            <a href="{{ route('events.edit', $event->id) }}" style="color: #b3b3b3; text-decoration: none; font-size: 0.8rem;">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Xóa sự kiện này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ff4d4d; cursor: pointer; font-size: 0.8rem; padding: 0;">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($events->isEmpty())
        <div style="text-align: center; color: #b3b3b3; padding: 80px 0;">
            <i class="fas fa-music" style="font-size: 64px; margin-bottom: 20px; opacity: 0.2;"></i>
            <p>Hiện tại chưa có sự kiện âm nhạc nào.</p>
        </div>
    @endif
</div>
@endsection
