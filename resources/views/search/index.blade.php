@extends('layouts.app')

@section('title', 'Tìm kiếm - Music Platform')

@section('content')
<div style="max-width: 900px; margin: auto;">
    
    <!-- Search Header (Only show if keyword exists) -->
    @if(!$keyword)
        <div style="text-align: center; color: #b3b3b3; padding: 60px 0;">
            <i class="fas fa-search" style="font-size: 80px; margin-bottom: 20px; opacity: 0.1;"></i>
            <h2 style="color: white;">Khám phá thế giới âm nhạc</h2>
            <p>Tìm kiếm bài hát, nghệ sĩ hoặc thể loại mà bạn yêu thích.</p>
        </div>
    @endif

    @if($keyword)
        <h2 style="margin-bottom: 30px;">Kết quả tìm kiếm cho "{{ $keyword }}"</h2>

        <!-- Artists Results -->
        @if($artists->isNotEmpty())
            <div style="margin-bottom: 40px;">
                <h3 style="border-bottom: 1px solid #333; padding-bottom: 10px; margin-bottom: 20px;">Nghệ sĩ</h3>
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    @foreach($artists as $artist)
                        <div style="text-align: center; width: 140px; background: #181818; padding: 15px; border-radius: 10px; transition: background 0.3s;" onmouseover="this.style.background='#282828'" onmouseout="this.style.background='#181818'">
                            <div style="width: 100px; height: 100px; background: #333; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 40px; box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
                                👤
                            </div>
                            <div style="font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $artist->username }}</div>
                            <div style="font-size: 12px; color: #b3b3b3; margin-top: 5px;">Nghệ sĩ</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Songs Results -->
        <div style="margin-bottom: 40px;">
            <h3 style="border-bottom: 1px solid #333; padding-bottom: 10px; margin-bottom: 20px;">Bài hát</h3>
            @if($songs->isNotEmpty())
                <div style="background: #181818; border-radius: 12px; overflow: hidden; border: 1px solid #333;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tbody>
                            @foreach($songs as $index => $song)
                                <tr style="border-bottom: 1px solid #282828; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 12px 20px;">
                                        <div style="display: flex; align-items: center; gap: 15px;">
                                            <img src="{{ $song->cover_image ?? '/assets/images/default-cover.png' }}" alt="cover" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                                            <div>
                                                <a href="{{ route('home', ['song_id' => $song->id]) }}" style="font-weight: bold; text-decoration: none; color: white;">
                                                    {{ $song->title }}
                                                </a>
                                                <div style="font-size: 0.8rem; color: #b3b3b3;">{{ $song->artist->username }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 12px 20px; text-align: right;">
                                        <div style="display: flex; gap: 15px; justify-content: flex-end;">
                                            <form action="{{ route('favorites.toggle') }}" method="POST" style="margin:0">
                                                @csrf
                                                <input type="hidden" name="song_id" value="{{ $song->id }}">
                                                <button type="submit" style="background:none; border:none; color:#b3b3b3; cursor:pointer;" title="Yêu thích">❤️</button>
                                            </form>
                                            <a href="{{ route('home', ['song_id' => $song->id]) }}" style="color: #1DB954; font-size: 1.2rem;" title="Phát ngay">
                                                <i class="fas fa-play-circle"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="color: #b3b3b3; text-align: center; padding: 40px;">Không tìm thấy bài hát nào phù hợp.</p>
            @endif
        </div>

    @else
        <div style="text-align: center; color: #b3b3b3; padding: 100px 0;">
            <i class="fas fa-search" style="font-size: 80px; margin-bottom: 20px; opacity: 0.1;"></i>
            <h2>Tìm kiếm mọi thứ bạn yêu thích</h2>
            <p>Tìm kiếm bài hát, nghệ sĩ hoặc podcast.</p>
        </div>
    @endif

</div>
@endsection
