@extends('layouts.app')

@section('title', 'Thanh toán - Music Platform')

@section('content')
<div style="max-width: 800px; margin: auto;">
    <h2 style="margin-bottom: 30px;">🏠 Thông tin nhận hàng</h2>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 30px; align-items: start;">
        
        <!-- Form thông tin -->
        <div style="background: #181818; padding: 30px; border-radius: 12px; border: 1px solid #333;">
            <form action="{{ route('discs.order.process') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #b3b3b3; font-size: 0.9rem;">HỌ VÀ TÊN NGƯỜI NHẬN</label>
                    <input type="text" name="receiver_name" required style="width: 100%; padding: 12px; background: #282828; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box;" value="{{ auth()->user()->username }}">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #b3b3b3; font-size: 0.9rem;">SỐ ĐIỆN THOẠI</label>
                    <input type="text" name="phone" required style="width: 100%; padding: 12px; background: #282828; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box;" value="{{ auth()->user()->phone }}">
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; color: #b3b3b3; font-size: 0.9rem;">ĐỊA CHỈ GIAO HÀNG</label>
                    <textarea name="address" required rows="3" style="width: 100%; padding: 12px; background: #282828; border: 1px solid #444; border-radius: 6px; color: white; box-sizing: border-box; resize: none;" placeholder="Nhập địa chỉ đầy đủ (Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố)"></textarea>
                </div>

                <button type="submit" style="width: 100%; background: #1DB954; color: black; border: none; padding: 16px; border-radius: 50px; font-weight: bold; cursor: pointer; font-size: 1.1rem; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    ĐẶT HÀNG NGAY
                </button>
            </form>
        </div>

        <!-- Tóm tắt đơn hàng -->
        <div style="background: #181818; padding: 30px; border-radius: 12px; border: 1px solid #333;">
            <h3 style="margin-top: 0; margin-bottom: 25px; font-size: 1.3rem; border-bottom: 1px solid #333; padding-bottom: 15px;">Tổng kết đơn hàng</h3>

            <div style="margin-bottom: 20px;">
                @foreach($cart as $item)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: #b3b3b3; font-size: 0.95rem;">
                        <span>{{ $item['title'] }}</span>
                        <span style="color: #fff;">{{ number_format($item['price']) }} VNĐ</span>
                    </div>
                @endforeach
            </div>

            <!-- Phần mã giảm giá -->
            <form action="{{ route('discs.coupon.apply') }}" method="POST" style="margin-bottom: 10px; display: flex; gap: 8px;">
                @csrf
                <input type="text" id="coupon_input" name="code" placeholder="Mã giảm giá" style="flex: 1; background: #121212; border: 1px solid #444; border-radius: 4px; padding: 10px; color: white; font-size: 0.9rem;" value="{{ session('coupon')['code'] ?? '' }}">
                <button type="submit" style="background: #333; color: white; border: none; padding: 0 15px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 0.85rem;">Áp dụng</button>
            </form>

            @if($suggestedCoupons->count() > 0)
                <div style="margin-bottom: 25px;">
                    <div style="font-size: 0.75rem; color: #777; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Gợi ý cho bạn:</div>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @foreach($suggestedCoupons as $sCoupon)
                            <div onclick="applySuggestedCode('{{ $sCoupon->code }}')" style="background: rgba(29, 185, 84, 0.1); border: 1px dashed #1DB954; color: #1DB954; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; cursor: pointer; transition: all 0.2s; font-weight: bold;" onmouseover="this.style.background='rgba(29, 185, 84, 0.2)'" onmouseout="this.style.background='rgba(29, 185, 84, 0.1)'">
                                <i class="fas fa-tag" style="font-size: 0.7rem;"></i> {{ $sCoupon->code }}
                                <div style="font-size: 0.65rem; font-weight: normal; margin-top: 2px;">Giảm {{ $sCoupon->type == 'percent' ? $sCoupon->value . '%' : number_format($sCoupon->value) . 'đ' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <script>
                    function applySuggestedCode(code) {
                        document.getElementById('coupon_input').value = code;
                        // Thêm hiệu ứng nháy nhẹ để người dùng biết mã đã được chọn
                        const input = document.getElementById('coupon_input');
                        input.style.borderColor = '#1DB954';
                        setTimeout(() => input.style.borderColor = '#444', 500);
                    }
                </script>
            @endif

            @if(session('success'))
                <div style="color: #1DB954; font-size: 0.8rem; margin-top: -15px; margin-bottom: 15px;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="color: #ff4444; font-size: 0.8rem; margin-top: -15px; margin-bottom: 15px;">{{ session('error') }}</div>
            @endif

            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.9rem;">
                <div style="display: flex; justify-content: space-between; color: #b3b3b3;">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($subtotal) }} VNĐ</span>
                </div>
                <div style="display: flex; justify-content: space-between; color: #b3b3b3;">
                    <span>Phí vận chuyển:</span>
                    <span>{{ number_format($shipping_fee) }} VNĐ</span>
                </div>
                @if($discount > 0)
                    <div style="display: flex; justify-content: space-between; color: #1DB954;">
                        <span>Giảm giá:</span>
                        <span>-{{ number_format($discount) }} VNĐ</span>
                    </div>
                @endif
                <div style="display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 1px solid #333; font-size: 1.2rem; font-weight: bold; color: #1DB954;">
                    <span>TỔNG CỘNG:</span>
                    <span>{{ number_format($subtotal + $shipping_fee - $discount) }} VNĐ</span>
                </div>
            </div>
            
            <div style="margin-top: 25px; font-size: 0.8rem; color: #777; text-align: center;">
                <i class="fas fa-truck"></i> Giao hàng dự kiến trong 3-5 ngày
            </div>
        </div>

    </div>
</div>
@endsection
