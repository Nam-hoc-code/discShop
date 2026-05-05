<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { color: #1DB954; font-size: 28px; font-weight: bold; text-decoration: none; }
        .status-badge { display: inline-block; padding: 8px 16px; background: #1DB954; color: white; border-radius: 20px; font-weight: bold; margin: 20px 0; }
        .order-details { background: #f9f9f9; padding: 20px; border-radius: 6px; margin: 20px 0; border: 1px solid #eee; }
        .footer { text-align: center; color: #777; font-size: 12px; margin-top: 30px; }
        .button { display: inline-block; padding: 12px 24px; background: #1DB954; color: white; text-decoration: none; border-radius: 25px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ url('/') }}" class="logo">MUSIC PLATFORM</a>
        </div>

        <h2>Chào {{ $order->user->username }},</h2>
        <p>Chúng tôi xin thông báo trạng thái đơn hàng <strong>#{{ $order->id }}</strong> của bạn vừa được cập nhật bởi nghệ sĩ.</p>

        <div style="text-align: center;">
            <div class="status-badge">{{ $statusText }}</div>
        </div>

        <div class="order-details">
            <h3 style="margin-top: 0;">Chi tiết đơn hàng:</h3>
            <p><strong>Sản phẩm:</strong> {{ $order->disc->title }}</p>
            <p><strong>Người nhận:</strong> {{ $order->user->username }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->user->address ?? 'N/A' }}</p>
            <p><strong>Tổng cộng:</strong> {{ number_format($order->disc->price) }} VNĐ</p>
        </div>

        <p>Bạn có thể theo dõi tiến độ đơn hàng trong mục <strong>Hồ sơ > Lịch sử đơn hàng</strong> trên website của chúng tôi.</p>

        <div style="text-align: center;">
            <a href="{{ route('profile.index') }}" class="button" style="color: white;">XEM ĐƠN HÀNG</a>
        </div>

        <p>Cảm ơn bạn đã tin tưởng và ủng hộ nghệ sĩ!</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Music Platform. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
