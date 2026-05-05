<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #1DB954; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #1DB954; margin: 0; font-size: 24px; }
        .order-info { margin-bottom: 30px; }
        .order-info h2 { font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        .table th { background-color: #f9f9f9; font-weight: bold; }
        .total { text-align: right; font-size: 20px; font-weight: bold; color: #1DB954; padding-top: 20px; }
        .footer { text-align: center; color: #777; font-size: 12px; margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px; }
        .btn { display: inline-block; padding: 12px 25px; background-color: #1DB954; color: #fff; text-decoration: none; border-radius: 50px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cảm ơn bạn đã đặt hàng!</h1>
            <p>Đơn hàng của bạn đã được tiếp nhận và đang chờ nghệ sĩ xác nhận.</p>
        </div>

        <div class="order-info">
            <h2>Thông tin người nhận</h2>
            <p><strong>Họ tên:</strong> {{ $receiver_name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $address }}</p>
        </div>

        <div class="order-info">
            <h2>Chi tiết đơn hàng</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm (Đĩa nhạc)</th>
                        <th style="text-align: right;">Giá</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cart as $item)
                        <tr>
                            <td>{{ $item['title'] }}</td>
                            <td style="text-align: right;">{{ number_format($item['price']) }} VNĐ</td>
                        </tr>
                        @php $total += $item['price']; @endphp
                    @endforeach
                </tbody>
            </table>
            <div class="total">
                Tổng cộng: {{ number_format($total) }} VNĐ
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/') }}" class="btn">Quay lại Music Platform</a>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Music Platform. All rights reserved.</p>
            <p>Đây là email tự động, vui lòng không phản hồi email này.</p>
        </div>
    </div>
</body>
</html>
