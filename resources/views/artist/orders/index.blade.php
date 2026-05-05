@extends('layouts.artist')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="header-section">
    <a href="{{ route('artist.dashboard') }}" class="back-btn"><i class="fas fa-chevron-left"></i></a>
    <i class="fa-solid fa-receipt" style="font-size: 2.2rem; color: #fff; margin-left: 10px;"></i>
    <h1>Đơn hàng đĩa nhạc</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div style="display: flex; flex-direction: column; gap: 40px;">
    
    <!-- Đơn hàng đang xử lý -->
    <div>
        <h2 style="margin-bottom: 20px; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-clock" style="color: #ffa500;"></i> Đơn hàng đang xử lý
        </h2>
        <div style="background: #181818; border-radius: 12px; overflow: hidden; border: 1px solid #333;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #333; text-align: left; color: #b3b3b3; font-size: 0.8rem; text-transform: uppercase;">
                        <th style="padding: 15px 20px;">MÃ ĐƠN</th>
                        <th style="padding: 15px 20px;">SẢN PHẨM (ĐĨA)</th>
                        <th style="padding: 15px 20px;">KHÁCH HÀNG</th>
                        <th style="padding: 15px 20px;">TRẠNG THÁI</th>
                        <th style="padding: 15px 20px;">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeOrders as $order)
                        <tr style="border-bottom: 1px solid #282828;">
                            <td style="padding: 15px 20px; font-weight: bold;">#{{ $order->id }}</td>
                            <td style="padding: 15px 20px;">
                                {{ $order->disc->songs->first()->title }}
                                @if($order->disc->songs->count() > 1)
                                    <span style="color: #1DB954; font-size: 0.8rem;">(Album)</span>
                                @endif
                            </td>
                            <td style="padding: 15px 20px;">{{ $order->user->username }}</td>
                            <td style="padding: 15px 20px;">
                                @php
                                    $statusMap = [
                                        'pending' => ['text' => 'Chờ xác nhận', 'color' => '#ffa500'],
                                        'confirmed' => ['text' => 'Đã xác nhận', 'color' => '#00dbff'],
                                        'shipping' => ['text' => 'Đang giao', 'color' => '#1db954'],
                                    ];
                                    $s = $statusMap[$order->status] ?? ['text' => $order->status, 'color' => 'white'];
                                @endphp
                                <span style="color: {{ $s['color'] }}; font-weight: bold;">{{ $s['text'] }}</span>
                            </td>
                            <td style="padding: 15px 20px; display: flex; gap: 10px;">
                                @if($order->status === 'pending')
                                    <form action="{{ route('artist.orders.status', $order->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" style="background: #00dbff; color: black; border: none; padding: 5px 12px; border-radius: 4px; font-weight: bold; cursor: pointer;">Xác nhận</button>
                                    </form>
                                @elseif($order->status === 'confirmed')
                                    <form action="{{ route('artist.orders.status', $order->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="shipping">
                                        <button type="submit" style="background: #1db954; color: black; border: none; padding: 5px 12px; border-radius: 4px; font-weight: bold; cursor: pointer;">Giao hàng</button>
                                    </form>
                                @elseif($order->status === 'shipping')
                                    <form action="{{ route('artist.orders.status', $order->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="done">
                                        <button type="submit" style="background: #b3b3b3; color: black; border: none; padding: 5px 12px; border-radius: 4px; font-weight: bold; cursor: pointer;">Hoàn tất</button>
                                    </form>
                                @endif

                                @if(in_array($order->status, ['pending', 'confirmed']))
                                    <form action="{{ route('artist.orders.status', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                        @csrf
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" style="background: none; border: 1px solid #ff4444; color: #ff4444; padding: 4px 10px; border-radius: 4px; font-weight: bold; cursor: pointer;">Hủy</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 40px; text-align: center; color: #b3b3b3;">Không có đơn hàng nào đang chờ xử lý.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Lịch sử đơn hàng (Logs) -->
    <div style="opacity: 0.8;">
        <h2 style="margin-bottom: 20px; font-size: 1.2rem; display: flex; align-items: center; gap: 10px; color: #b3b3b3;">
            <i class="fas fa-history"></i> Lịch sử đơn hàng (Nhật ký)
        </h2>
        <div style="background: #121212; border-radius: 12px; overflow: hidden; border: 1px solid #222;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #222; text-align: left; color: #777; font-size: 0.8rem; text-transform: uppercase;">
                        <th style="padding: 15px 20px;">MÃ ĐƠN</th>
                        <th style="padding: 15px 20px;">SẢN PHẨM</th>
                        <th style="padding: 15px 20px;">KHÁCH HÀNG</th>
                        <th style="padding: 15px 20px;">KẾT QUẢ</th>
                        <th style="padding: 15px 20px;">THỜI GIAN CẬP NHẬT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orderHistory as $order)
                        <tr style="border-bottom: 1px solid #222; color: #999;">
                            <td style="padding: 15px 20px;">#{{ $order->id }}</td>
                            <td style="padding: 15px 20px;">{{ $order->disc->songs->first()->title }}</td>
                            <td style="padding: 15px 20px;">{{ $order->user->username }}</td>
                            <td style="padding: 15px 20px;">
                                @if($order->status === 'done')
                                    <span style="color: #1db954;"><i class="fas fa-check-double"></i> Thành công</span>
                                @else
                                    <span style="color: #ff4444;"><i class="fas fa-times-circle"></i> Đã hủy</span>
                                @endif
                            </td>
                            <td style="padding: 15px 20px; font-size: 0.85rem;">{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 30px; text-align: center; color: #555;">Chưa có lịch sử đơn hàng.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
