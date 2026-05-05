@extends('layouts.app')

@section('title', $disc->title . ' - Music Platform')

@section('content')
<div style="max-width: 900px; margin: auto; display: grid; grid-template-columns: 400px 1fr; gap: 40px; align-items: start;">
    
    <div>
        <img src="{{ $disc->songs->first()->cover_image ?? '/assets/images/default-cover.png' }}" alt="cover" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
    </div>

    <div>
        <h1 style="margin: 0 0 10px 0; font-size: 2.2rem;">{{ $disc->title }}</h1>
        <h2 style="margin: 0 0 20px 0; color: #1DB954; font-size: 1.5rem;">{{ $disc->songs->first()->artist->username }}</h2>

        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 0.9rem; color: #b3b3b3; text-transform: uppercase; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px;">Danh sách bài hát</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach($disc->songs as $index => $song)
                    <li style="padding: 10px 15px; background: #121212; border-radius: 6px; margin-bottom: 5px; display: flex; align-items: center; gap: 15px;">
                        <span style="color: #555; font-weight: bold;">{{ $index + 1 }}</span>
                        <span style="font-weight: bold;">{{ $song->title }}</span>
                        <span style="margin-left: auto; color: #b3b3b3; font-size: 0.8rem; background: #282828; padding: 2px 8px; border-radius: 4px;">{{ $song->genre->name ?? 'N/A' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div style="background: #181818; padding: 24px; border-radius: 12px; border: 1px solid #333; margin-bottom: 30px;">
            <div style="font-size: 0.9rem; color: #b3b3b3; margin-bottom: 5px;">GIÁ BÁN</div>
            <div style="font-size: 2rem; font-weight: bold; color: white;">{{ number_format($disc->price) }} VNĐ</div>
        </div>

        <p style="color: #b3b3b3; line-height: 1.6; margin-bottom: 40px;">
            Sở hữu ngay đĩa vật lý của các bản hit từ {{ $disc->songs->first()->artist->username }}. Mỗi chiếc đĩa đều được đóng gói cẩn thận và gửi trực tiếp từ nghệ sĩ đến tay bạn.
        </p>

        <div style="display: flex; gap: 15px; align-items: center;">
            <form action="{{ route('discs.cart.add') }}" method="POST" style="flex: 1;">
                @csrf
                <input type="hidden" name="disc_id" value="{{ $disc->id }}">
                <button type="submit" style="width: 100%; background: #1DB954; color: black; border: none; padding: 18px; border-radius: 50px; font-weight: bold; cursor: pointer; font-size: 1.2rem; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <i class="fas fa-shopping-cart"></i> THÊM VÀO GIỎ HÀNG
                </button>
            </form>

            @auth
                <form action="{{ route('favorites.toggle_disc') }}" method="POST">
                    @csrf
                    <input type="hidden" name="disc_id" value="{{ $disc->id }}">
                    <button type="submit" style="background: #282828; color: {{ $isFavorited ? '#1DB954' : 'white' }}; border: 1px solid #333; width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.5rem; transition: all 0.2s;" onmouseover="this.style.background='#333'; this.style.transform='scale(1.05)'" onmouseout="this.style.background='#282828'; this.style.transform='scale(1)'">
                        <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart"></i>
                    </button>
                </form>
            @endauth
        </div>

        <a href="{{ route('discs.index') }}" style="display: block; text-align: center; margin-top: 20px; color: #b3b3b3; text-decoration: none;">
            ⬅ Quay lại cửa hàng
        </a>
    </div>

</div>
@endsection
