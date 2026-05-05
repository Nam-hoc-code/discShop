@extends('layouts.app')

@section('title', 'Yêu thích của bạn - Music Platform')

@section('content')
<div style="max-width: 1000px; margin: auto;">
    
    @if(session('success'))
        <div style="background: rgba(29, 185, 84, 0.2); color: #1DB954; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- SECTION: FAVORITE DISCS (PRODUCTS) -->
    <h2 style="margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-compact-disc" style="color: #1DB954;"></i> Sản phẩm yêu thích (Đĩa nhạc)
    </h2>
    
    @if($favoriteDiscs->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-bottom: 50px;">
            @foreach($favoriteDiscs as $favDisc)
                @php $disc = $favDisc->disc; @endphp
                <div style="background: #181818; padding: 15px; border-radius: 12px; border: 1px solid #333; position: relative;">
                    <a href="{{ route('discs.show', $disc->id) }}" style="text-decoration: none; color: white;">
                        <img src="{{ $disc->songs->first()->cover_image ?? '/assets/images/default-cover.png' }}" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 8px; margin-bottom: 12px;">
                        <h4 style="margin: 0 0 5px 0; font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $disc->title }}</h4>
                    </a>
                    <p style="color: #b3b3b3; font-size: 0.85rem; margin: 0 0 10px 0;">{{ $disc->songs->first()->artist->username }}</p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: bold; color: #1DB954;">{{ number_format($disc->price) }}đ</span>
                        <form action="{{ route('favorites.remove_disc', $favDisc->id) }}" method="POST" onsubmit="return confirm('Xóa khỏi yêu thích?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #ff4d4d; cursor: pointer; font-size: 0.85rem;">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; color: #b3b3b3; padding: 40px; background: #181818; border-radius: 12px; margin-bottom: 50px; border: 1px solid #333;">
            <p>Bạn chưa yêu thích đĩa nhạc nào. <a href="{{ route('discs.index') }}" style="color: #1DB954; font-weight: bold;">Đi xem đĩa ngay</a></p>
        </div>
    @endif

    <!-- SECTION: FAVORITE SONGS -->
    <h2 style="margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-heart" style="color: #ff4d4d;"></i> Bài hát yêu thích
    </h2>

    @if($favorites->count() > 0)
        <div style="background: #181818; border-radius: 12px; overflow: hidden; border: 1px solid #333;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #333; text-align: left; color: #b3b3b3; font-size: 0.8rem; text-transform: uppercase;">
                        <th style="padding: 15px 20px;"># TIÊU ĐỀ</th>
                        <th style="padding: 15px 20px;">NGHỆ SĨ</th>
                        <th style="padding: 15px 20px;">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($favorites as $index => $fav)
                        <tr style="border-bottom: 1px solid #282828; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 12px 20px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <span style="color: #b3b3b3; width: 20px;">{{ $index + 1 }}</span>
                                    <img src="{{ $fav->song->cover_image ?? '/assets/images/default-cover.png' }}" alt="cover" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                                    <a href="{{ route('home', ['song_id' => $fav->song->id]) }}" style="font-weight: bold; text-decoration: none;">
                                        {{ $fav->song->title }}
                                    </a>
                                </div>
                            </td>
                            <td style="padding: 12px 20px; color: #b3b3b3;">
                                {{ $fav->song->artist->username }}
                            </td>
                            <td style="padding: 12px 20px;">
                                <form action="{{ route('favorites.remove', $fav->id) }}" method="POST" onsubmit="return confirm('Xóa khỏi yêu thích?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ff4d4d; cursor: pointer; font-size: 0.9rem;">
                                        <i class="fas fa-heart-broken"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; color: #b3b3b3; padding: 40px; background: #181818; border-radius: 12px; border: 1px solid #333;">
            <p>Bạn chưa có bài hát yêu thích nào. <a href="{{ route('home') }}" style="color: #1DB954; font-weight: bold;">Khám phá ngay</a></p>
        </div>
    @endif

</div>
@endsection
