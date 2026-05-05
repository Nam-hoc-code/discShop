@extends('layouts.app')

@section('title', $artist->username . ' - Nghệ sĩ')

@section('content')
<div style="margin-top: -30px; margin-left: -30px; margin-right: -30px;">
    <!-- Banner Nghệ sĩ -->
    <div style="height: 350px; position: relative; background: linear-gradient(to bottom, #404040, #121212); display: flex; align-items: flex-end; padding: 40px 60px;">
        <div style="display: flex; align-items: center; gap: 30px; z-index: 2;">
            <div style="width: 200px; height: 200px; border-radius: 50%; background: #333; box-shadow: 0 15px 35px rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; font-size: 80px; overflow: hidden; border: 5px solid #1DB954;">
                👤
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <i class="fas fa-check-circle" style="color: #3d91ff; font-size: 20px;"></i>
                    <span style="font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">Nghệ sĩ đã xác minh</span>
                </div>
                <h1 style="font-size: 80px; margin: 0; font-weight: 900; letter-spacing: -3px;">{{ $artist->username }}</h1>
                <div style="font-size: 16px; margin-top: 15px; font-weight: bold; color: #fff;">
                    {{ number_format($artist->followers_count) }} người theo dõi
                </div>
            </div>
        </div>
    </div>

    <!-- Thanh công cụ -->
    <div style="padding: 30px 60px; display: flex; align-items: center; gap: 30px;">
        @auth
            <form action="{{ route('artist.follow', $artist->id) }}" method="POST">
                @csrf
                <button type="submit" style="background: {{ Auth::user()->followings()->where('artist_id', $artist->id)->exists() ? 'transparent' : '#1DB954' }}; color: {{ Auth::user()->followings()->where('artist_id', $artist->id)->exists() ? '#fff' : '#000' }}; border: {{ Auth::user()->followings()->where('artist_id', $artist->id)->exists() ? '1px solid #777' : 'none' }}; padding: 12px 30px; border-radius: 50px; font-weight: bold; font-size: 14px; cursor: pointer; transition: transform 0.2s; text-transform: uppercase;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    {{ Auth::user()->followings()->where('artist_id', $artist->id)->exists() ? 'Đang theo dõi' : 'Theo dõi' }}
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" style="background: #1DB954; color: #000; padding: 12px 30px; border-radius: 50px; font-weight: bold; text-transform: uppercase; font-size: 14px;">Theo dõi</a>
        @endauth

        <button style="background: none; border: none; color: #b3b3b3; font-size: 24px; cursor: pointer;"><i class="fas fa-ellipsis-h"></i></button>
    </div>

    <div style="padding: 0 60px; display: grid; grid-template-columns: 2fr 1fr; gap: 60px; margin-bottom: 50px;">
        <!-- Danh sách bài hát -->
        <div>
            <h2 style="margin-bottom: 25px; font-size: 24px;">Bài hát phổ biến</h2>
            <div style="display: flex; flex-direction: column;">
                @forelse($artist->songs as $index => $song)
                    <div class="song-row" style="display: grid; grid-template-columns: 50px 1fr 100px; align-items: center; padding: 12px 15px; border-radius: 6px; transition: background 0.3s;" onmouseover="this.style.background='#282828'" onmouseout="this.style.background='transparent'">
                        <span style="color: #b3b3b3; font-size: 16px;">{{ $index + 1 }}</span>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img src="{{ $song->cover_image ?? '/assets/images/default-cover.png' }}" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                            <div>
                                <a href="{{ route('home', ['song_id' => $song->id]) }}" style="font-weight: bold; display: block; color: white;">{{ $song->title }}</a>
                                <span style="font-size: 12px; color: #b3b3b3;">{{ number_format($song->views ?? 0) }} lượt nghe</span>
                            </div>
                        </div>
                        <div style="text-align: right; color: #b3b3b3; font-size: 14px;">
                            <i class="far fa-heart" style="cursor: pointer; margin-right: 15px;"></i>
                            {{ $song->duration ?? '3:45' }}
                        </div>
                    </div>
                @empty
                    <p style="color: #b3b3b3;">Chưa có bài hát nào được đăng.</p>
                @endforelse
            </div>
        </div>

        <!-- Thông tin thêm -->
        <div>
            <h2 style="margin-bottom: 25px; font-size: 24px;">Giới thiệu</h2>
            <div style="background: #181818; padding: 25px; border-radius: 12px; line-height: 1.6; color: #b3b3b3;">
                <p>Nghệ sĩ <strong>{{ $artist->username }}</strong> đã tham gia nền tảng từ ngày {{ $artist->created_at->format('d/m/Y') }}.</p>
                <div style="margin-top: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>Số bài hát:</span>
                        <span style="color: white; font-weight: bold;">{{ $artist->songs->count() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>Số Album/Đĩa:</span>
                        <span style="color: white; font-weight: bold;">{{ $artist->discs->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần Đĩa nhạc / Album -->
    @if($artist->discs->count() > 0)
        <div style="padding: 0 60px; margin-bottom: 80px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px;">
                <h2 style="margin: 0; font-size: 24px;">Album & Đĩa nhạc</h2>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px;">
                @foreach($artist->discs as $disc)
                    <a href="{{ route('discs.show', $disc->id) }}" class="card-link" style="background: #181818; padding: 18px; border-radius: 8px; transition: background 0.3s; display: block;" onmouseover="this.style.background='#282828'" onmouseout="this.style.background='#181818'">
                        <div style="position: relative; margin-bottom: 15px;">
                            <img src="{{ $disc->songs->first()->cover_image ?? '/assets/images/default-cover.png' }}" style="width: 100%; aspect-ratio: 1; border-radius: 6px; box-shadow: 0 8px 24px rgba(0,0,0,0.5); object-fit: cover;">
                            <div class="play-btn-card" style="position: absolute; right: 10px; bottom: 10px; width: 45px; height: 45px; background: #1DB954; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(0,0,0,0.3); opacity: 0; transition: all 0.3s; transform: translateY(10px);">
                                <i class="fas fa-play" style="color: black; font-size: 18px; margin-left: 3px;"></i>
                            </div>
                        </div>
                        <h3 style="margin: 0 0 8px 0; font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: white;">{{ $disc->title }}</h3>
                        <p style="margin: 0; font-size: 14px; color: #b3b3b3;">{{ $disc->created_at->format('Y') }} • {{ $disc->songs->count() }} bài hát</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    .card-link:hover .play-btn-card {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
</style>
@endsection
