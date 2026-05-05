@extends('layouts.app')

@section('title', 'Danh sách đĩa nhạc - Music Platform')

@section('content')
<div style="max-width: 1000px; margin: auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">💿 Danh sách đĩa nhạc hiện có</h2>
        <a href="{{ route('discs.cart') }}" style="background: #1DB954; color: black; padding: 10px 20px; border-radius: 50px; font-weight: bold; text-decoration: none;">
            🛒 Xem giỏ hàng ({{ count(session('cart', [])) }})
        </a>
    </div>
    
    <!-- Thanh tìm kiếm -->
    <div style="margin-bottom: 25px;">
        <form action="{{ route('discs.index') }}" method="GET" style="display: flex; gap: 10px; align-items: stretch;">
            @if(request('genre_id'))
                <input type="hidden" name="genre_id" value="{{ request('genre_id') }}">
            @endif
            <div style="flex: 1; position: relative; min-width: 0;">
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #b3b3b3;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên đĩa nhạc hoặc nghệ sĩ..." style="width: 100%; padding: 12px 12px 12px 45px; background: #282828; border: 1px solid #333; border-radius: 50px; color: white; outline: none; font-size: 0.95rem; box-sizing: border-box;" onfocus="this.style.borderColor='#1DB954'; this.style.background='#333'" onblur="this.style.borderColor='#333'; this.style.background='#282828'">
            </div>
            <button type="submit" style="background: #1DB954; color: black; border: none; padding: 0 30px; border-radius: 50px; font-weight: bold; cursor: pointer; transition: all 0.2s; white-space: nowrap; flex-shrink: 0;" onmouseover="this.style.transform='scale(1.02)'; this.style.background='#1ed760'" onmouseout="this.style.transform='scale(1)'; this.style.background='#1DB954'">Tìm kiếm</button>
            @if(request('search') || request('genre_id'))
                <a href="{{ route('discs.index') }}" style="background: #333; color: white; padding: 0 20px; border-radius: 50px; font-weight: bold; text-decoration: none; font-size: 0.9rem; display: flex; align-items: center; white-space: nowrap; flex-shrink: 0; border: 1px solid #444;" onmouseover="this.style.background='#444'" onmouseout="this.style.background='#333'">Đặt lại</a>
            @endif
        </form>
    </div>

    <!-- Bộ lọc thể loại -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 30px; border-bottom: 1px solid #333; padding-bottom: 15px;">
        <a href="{{ route('discs.index') }}" style="padding: 8px 18px; border-radius: 20px; background: {{ !request('genre_id') ? '#1DB954' : '#282828' }}; color: {{ !request('genre_id') ? 'black' : 'white' }}; font-weight: bold; font-size: 0.9rem;">Tất cả</a>
        @foreach($genres as $genre)
            <a href="{{ route('discs.index', ['genre_id' => $genre->id]) }}" style="padding: 8px 18px; border-radius: 20px; background: {{ request('genre_id') == $genre->id ? '#1DB954' : '#282828' }}; color: {{ request('genre_id') == $genre->id ? 'black' : 'white' }}; font-weight: bold; font-size: 0.9rem;">{{ $genre->name }}</a>
        @endforeach
    </div>



    <!-- Đĩa nhạc yêu thích (Chỉ hiện khi có) -->
    @if($favoriteDiscs->count() > 0)
        <div style="margin-bottom: 40px; padding: 20px; background: rgba(29, 185, 84, 0.05); border-radius: 12px; border: 1px solid rgba(29, 185, 84, 0.2);">
            <h3 style="margin-top: 0; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: #1DB954;">
                <i class="fas fa-heart"></i> Đĩa nhạc bạn đã yêu thích
            </h3>
            <div style="display: flex; gap: 20px; overflow-x: auto; padding-bottom: 10px; scrollbar-width: thin; scrollbar-color: #333 transparent;">
                @foreach($favoriteDiscs as $fav)
                    @php $disc = $fav->disc; @endphp
                    <div style="min-width: 160px; width: 160px; background: #181818; padding: 12px; border-radius: 8px; border: 1px solid #333; position: relative;">
                        <a href="{{ route('discs.show', $disc->id) }}" style="text-decoration: none; color: white;">
                            <img src="{{ $disc->songs->first()->cover_image ?? '/assets/images/default-cover.png' }}" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 4px; margin-bottom: 10px;">
                            <div style="font-weight: bold; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $disc->title }}</div>
                        </a>
                        <div style="font-size: 0.75rem; color: #b3b3b3;">{{ $disc->songs->first()->artist->username }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 24px;">
        @foreach($discs as $disc)
            <div style="background: #181818; padding: 20px; border-radius: 12px; transition: background 0.3s; border: 1px solid #333; position: relative;" onmouseover="this.style.background='#282828'" onmouseout="this.style.background='#181818'">
                @if($disc->created_at->diffInDays(now()) < 7)
                    <div style="position: absolute; top: 10px; right: 10px; background: #1DB954; color: black; font-size: 0.7rem; font-weight: bold; padding: 4px 8px; border-radius: 4px; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.5);">NEW</div>
                @endif
                <a href="{{ route('discs.show', $disc->id) }}" style="text-decoration: none; color: white; display: block;">
                    <img src="{{ $disc->songs->first()->cover_image ?? '/assets/images/default-cover.png' }}" alt="cover" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <h3 style="margin: 0 0 5px 0; font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $disc->title }}
                    </h3>
                </a>
                <p style="color: #b3b3b3; margin: 0 0 10px 0;">{{ $disc->songs->first()->artist->username }}</p>
                <div style="margin-bottom: 15px;">
                    <span style="background: #333; color: #b3b3b3; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ $disc->songs->first()->genre->name ?? 'Không phân loại' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: bold; color: #1DB954; font-size: 1.2rem;">{{ number_format($disc->price) }} VNĐ</span>
                    <div style="display: flex; gap: 8px;">
                        @auth
                            <form action="{{ route('favorites.toggle_disc') }}" method="POST">
                                @csrf
                                <input type="hidden" name="disc_id" value="{{ $disc->id }}">
                                <button type="submit" style="background: #333; color: {{ in_array($disc->id, $favoriteDiscIds) ? '#1DB954' : 'white' }}; border: none; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class="{{ in_array($disc->id, $favoriteDiscIds) ? 'fas' : 'far' }} fa-heart"></i>
                                </button>
                            </form>
                        @endauth
                        <form action="{{ route('discs.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="disc_id" value="{{ $disc->id }}">
                            <button type="submit" style="background: #1DB954; color: black; border: none; padding: 8px 15px; border-radius: 20px; font-weight: bold; cursor: pointer; font-size: 0.9rem;">
                                ➕ Thêm
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($discs->isEmpty())
        <div style="text-align: center; color: #b3b3b3; padding: 50px;">
            <i class="fas fa-compact-disc" style="font-size: 48px; margin-bottom: 15px;"></i>
            <p>Hiện tại chưa có đĩa nhạc nào được rao bán.</p>
        </div>
    @endif
</div>
@endsection
