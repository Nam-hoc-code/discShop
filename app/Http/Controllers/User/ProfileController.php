<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiscOrder;
use App\Models\User;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Lấy lịch sử đơn hàng của người dùng này
        $orders = DiscOrder::with('disc.songs')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Lấy đĩa nhạc yêu thích
        $favoriteDiscs = $user->favoriteDiscs()->with(['disc.songs.artist'])->latest()->get();

        return view('user.profile', compact('user', 'orders', 'favoriteDiscs'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $oldData = $user->only(['username', 'email', 'phone']);
        $user->update($request->only(['username', 'email', 'phone']));

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'UPDATE_PROFILE',
            'target_type' => 'USER',
            'target_id' => $user->id,
            'description' => "Đã cập nhật thông tin cá nhân",
            'data' => json_encode([
                'old' => $oldData,
                'new' => $request->only(['username', 'email', 'phone'])
            ])
        ]);

        return back()->with('success', 'Đã cập nhật thông tin cá nhân thành công!');
    }

    public function confirmReceived($id)
    {
        $user = auth()->user();
        $order = DiscOrder::where('user_id', $user->id)
            ->where('status', 'SHIPPING')
            ->findOrFail($id);

        $order->update(['status' => 'COMPLETED']);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'CONFIRM_RECEIVED',
            'target_type' => 'ORDER',
            'target_id' => $order->id,
            'description' => "Người dùng đã xác nhận nhận được đơn hàng #{$order->id}",
            'data' => json_encode(['order_id' => $order->id])
        ]);

        return back()->with('success', 'Xác nhận đã nhận hàng thành công!');
    }
}
