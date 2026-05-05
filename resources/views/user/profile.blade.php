@extends('layouts.app')

@section('title', 'Hồ sơ & Đơn hàng')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    
    <div style="display: flex; gap: 30px; align-items: flex-start;">
        <!-- Thông tin cá nhân -->
        <!-- Thông tin cá nhân & Chỉnh sửa -->
        <div style="width: 35%; background: #181818; padding: 30px; border-radius: 12px; border: 1px solid #282828;">
            <div style="text-align: center; margin-bottom: 25px;">
                <div style="width: 100px; height: 100px; background: #333; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 40px; border: 3px solid #1DB954;">
                    👤
                </div>
                <h2 style="margin: 0;">{{ $user->username }}</h2>
                <span style="color: #1DB954; font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">{{ $user->role }}</span>
            </div>

            @if(session('success'))
                <div style="background: rgba(29, 185, 84, 0.1); color: #1DB954; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; color: #b3b3b3; font-size: 0.85rem;">TÊN NGƯỜI DÙNG</label>
                    <input type="text" name="username" value="{{ $user->username }}" required style="width: 100%; padding: 12px; background: #121212; border: 1px solid #333; border-radius: 6px; color: white; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; color: #b3b3b3; font-size: 0.85rem;">ĐỊA CHỈ EMAIL</label>
                    <input type="email" name="email" value="{{ $user->email }}" required style="width: 100%; padding: 12px; background: #121212; border: 1px solid #333; border-radius: 6px; color: white; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #b3b3b3; font-size: 0.85rem;">SỐ ĐIỆN THOẠI</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" style="width: 100%; padding: 12px; background: #121212; border: 1px solid #333; border-radius: 6px; color: white; font-size: 1rem;">
                </div>

                <button type="submit" style="width: 100%; background: #1DB954; color: black; border: none; padding: 14px; border-radius: 50px; font-weight: bold; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    CẬP NHẬT THÔNG TIN
                </button>
            </form>

            <div style="margin-top: 30px; font-size: 0.85rem; color: #555; text-align: center;">
                Tham gia từ: {{ $user->created_at->format('d/m/Y') }}
            </div>
        </div>

        <!-- Lịch sử đơn hàng -->
        <div style="width: 70%; background: #121212; padding: 0px;">
            <h2 style="margin-top: 0; margin-bottom: 20px;">Lịch sử đơn hàng</h2>

            @if($orders->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    @foreach($orders as $order)
                        <div style="background: #181818; border: 1px solid #282828; border-radius: 10px; overflow: hidden; display: flex; align-items: center; padding: 15px; gap: 20px; transition: background 0.3s;" onmouseover="this.style.background='#222'" onmouseout="this.style.background='#181818'">
                            <img src="{{ $order->disc->songs->first()->cover_image ?? '/assets/images/default-cover.png' }}" style="width: 60px; height: 60px; border-radius: 6px; object-fit: cover;">
                            
                            <div style="flex: 1;">
                                <div style="font-weight: bold; font-size: 16px;">
                                    {{ $order->disc->songs->first()->title }}
                                    @if($order->disc->songs->count() > 1)
                                        <span style="color: #1DB954; font-size: 12px;">(Album)</span>
                                    @endif
                                </div>
                                <div style="font-size: 13px; color: #b3b3b3;">{{ $order->disc->songs->first()->artist->username }}</div>
                                <div style="font-size: 12px; color: #777; margin-top: 5px;">Mã đơn: #{{ $order->id }} | Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</div>
                            </div>

                            <div style="text-align: right;">
                                <div style="font-weight: bold; color: #1DB954; font-size: 16px;">{{ number_format($order->disc->price) }} VNĐ</div>
                                
                                @php
                                    $statusColor = '#b3b3b3';
                                    $statusText = $order->status;
                                    if($order->status == 'PENDING') { $statusColor = '#ffa500'; $statusText = 'Đang chờ duyệt'; }
                                    elseif($order->status == 'SHIPPING') { $statusColor = '#00DBFF'; $statusText = 'Đang giao hàng'; }
                                    elseif($order->status == 'COMPLETED') { $statusColor = '#1DB954'; $statusText = 'Đã giao thành công'; }
                                    elseif($order->status == 'CANCELLED') { $statusColor = '#ff4444'; $statusText = 'Đã hủy'; }
                                @endphp
                                
                                <div style="margin-top: 8px;">
                                    <span style="background: {{ $statusColor }}22; color: {{ $statusColor }}; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; border: 1px solid {{ $statusColor }}44;">
                                        ● {{ $statusText }}
                                    </span>
                                </div>

                                @if($order->status == 'SHIPPING')
                                    <form action="{{ route('profile.order.confirm', $order->id) }}" method="POST" style="margin-top: 10px;">
                                        @csrf
                                        <button type="submit" style="background: #1DB954; color: black; border: none; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: bold; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                            XÁC NHẬN ĐÃ NHẬN
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 60px; background: #181818; border-radius: 12px; color: #b3b3b3;">
                    <div style="font-size: 50px; margin-bottom: 15px;"><i class="fas fa-shopping-bag"></i></div>
                    <p>Bạn chưa mua đĩa nhạc nào.</p>
                    <a href="{{ route('discs.index') }}" style="color: #1DB954; font-weight: bold; text-decoration: underline;">Khám phá cửa hàng ngay</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Đĩa nhạc yêu thích -->
    <div style="margin-top: 50px; border-top: 1px solid #282828; padding-top: 30px;">
        <h2 style="margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-heart" style="color: #1DB954;"></i> Đĩa nhạc yêu thích
        </h2>

        @if($favoriteDiscs->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
                @foreach($favoriteDiscs as $fav)
                    @php $disc = $fav->disc; @endphp
                    <div style="background: #181818; padding: 15px; border-radius: 12px; border: 1px solid #333; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        <a href="{{ route('discs.show', $disc->id) }}" style="text-decoration: none; color: white;">
                            <img src="{{ $disc->songs->first()->cover_image ?? '/assets/images/default-cover.png' }}" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 8px; margin-bottom: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                            <h4 style="margin: 0 0 5px 0; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $disc->title }}</h4>
                        </a>
                        <p style="color: #b3b3b3; font-size: 0.8rem; margin: 0;">{{ $disc->songs->first()->artist->username }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 40px; background: #181818; border-radius: 12px; color: #b3b3b3; border: 1px dashed #333;">
                <p>Bạn chưa yêu thích đĩa nhạc nào.</p>
                <a href="{{ route('discs.index') }}" style="color: #1DB954; font-weight: bold; text-decoration: none;">Khám phá cửa hàng ngay</a>
            </div>
        @endif
    </div>

</div>
@endsection
