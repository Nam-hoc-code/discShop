@extends('layouts.app')

@section('title', 'Giỏ hàng - Music Platform')

@section('content')
<div style="max-width: 800px; margin: auto;">
    <h2 style="margin-bottom: 30px;">🛒 Giỏ hàng của bạn</h2>

    @if(session('success'))
        <div style="background: rgba(29, 185, 84, 0.2); color: #1DB954; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(!empty($cart))
        <div style="background: #181818; border-radius: 12px; overflow: hidden; border: 1px solid #333;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #333; text-align: left;">
                        <th style="padding: 20px;">Sản phẩm</th>
                        <th style="padding: 20px;">Giá</th>
                        <th style="padding: 20px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cart as $index => $item)
                        <tr style="border-bottom: 1px solid #282828;">
                            <td style="padding: 20px;">
                                <div style="font-weight: bold;">{{ $item['title'] }}</div>
                            </td>
                            <td style="padding: 20px;">
                                {{ number_format($item['price']) }} VNĐ
                            </td>
                            <td style="padding: 20px;">
                                <form action="{{ route('discs.cart.remove', $index) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ff4d4d; cursor: pointer; font-size: 0.9rem;">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @php $total += $item['price']; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #282828;">
                        <td style="padding: 20px; font-weight: bold; font-size: 1.2rem;">TỔNG CỘNG</td>
                        <td style="padding: 20px; font-weight: bold; font-size: 1.2rem; color: #1DB954;">{{ number_format($total) }} VNĐ</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('discs.index') }}" style="color: #b3b3b3; text-decoration: none;">⬅ Tiếp tục mua sắm</a>
            <a href="{{ route('discs.checkout') }}" style="background: #1DB954; color: black; padding: 14px 40px; border-radius: 50px; font-weight: bold; text-decoration: none; font-size: 1.1rem;">
                Tiến hành thanh toán ➔
            </a>
        </div>
    @else
        <div style="text-align: center; color: #b3b3b3; padding: 50px; background: #181818; border-radius: 12px; border: 1px solid #333;">
            <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 15px;"></i>
            <p>Giỏ hàng của bạn đang trống.</p>
            <a href="{{ route('discs.index') }}" style="color: #1DB954; text-decoration: none; font-weight: bold;">Đi mua sắm ngay</a>
        </div>
    @endif
</div>
@endsection
