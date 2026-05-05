<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Disc;
use App\Models\DiscOrder;
use Illuminate\Support\Facades\Session;

class DiscController extends Controller
{
    public function index(Request $request)
    {
        $query = Disc::with(['songs.artist', 'songs.genre']);
        
        // Lọc theo thể loại nếu có
        if ($request->has('genre_id') && $request->genre_id != '') {
            $query->whereHas('songs', function($q) use ($request) {
                $q->where('genre_id', $request->genre_id);
            });
        }

        // Tìm kiếm theo tên đĩa hoặc tên nghệ sĩ
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('songs.artist', function($sq) use ($search) {
                      $sq->where('username', 'like', "%{$search}%");
                  });
            });
        }
        
        $discs = $query->orderBy('id', 'desc')->get();
        $genres = \App\Models\Genre::all();
        
        $favoriteDiscIds = auth()->check() 
            ? auth()->user()->favoriteDiscs()->pluck('disc_id')->toArray() 
            : [];

        $favoriteDiscs = auth()->check()
            ? auth()->user()->favoriteDiscs()->with(['disc.songs.artist'])->latest()->get()
            : collect();
        
        return view('discs.index', compact('discs', 'genres', 'favoriteDiscIds', 'favoriteDiscs'));
    }

    public function show($id)
    {
        $disc = Disc::with(['songs.artist', 'songs.genre'])->findOrFail($id);
        $isFavorited = auth()->check() && auth()->user()->favoriteDiscs()->where('disc_id', $id)->exists();
        
        return view('discs.show', compact('disc', 'isFavorited'));
    }

    public function addToCart(Request $request)
    {
        $disc = Disc::with('songs')->findOrFail($request->disc_id);
        
        $cart = Session::get('cart', []);
        
        // Simple cart: add as new item every time or check if exists
        $cart[] = [
            'disc_id' => $disc->id,
            'title' => $disc->title,
            'price' => $disc->price,
        ];
        
        Session::put('cart', $cart);
        
        return back()->with('success', 'Đã thêm vào giỏ hàng thành công!');
    }

    public function cart()
    {
        $cart = Session::get('cart', []);
        return view('discs.cart', compact('cart'));
    }

    public function removeFromCart($index)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$index])) {
            unset($cart[$index]);
            Session::put('cart', array_values($cart));
        }
        return redirect()->route('discs.cart')->with('success', 'Đã xóa khỏi giỏ hàng!');
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('discs.index')->with('error', 'Giỏ hàng trống!');
        }

        $subtotal = collect($cart)->sum('price');
        $shipping_fee = 30000; // Phí ship mặc định
        
        $coupon = Session::get('coupon');
        $discount = 0;
        if ($coupon) {
            $couponModel = \App\Models\Coupon::findValid($coupon['code']);
            if ($couponModel) {
                $discount = $couponModel->calculateDiscount($subtotal);
            } else {
                Session::forget('coupon');
            }
        }

        // Lấy các mã giảm giá gợi ý (đang hoạt động và chưa hết hạn)
        $suggestedCoupons = \App\Models\Coupon::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now()->toDateString());
            })
            ->whereColumn('used_count', '<', 'usage_limit')
            ->limit(3)
            ->get();

        return view('discs.checkout', compact('cart', 'subtotal', 'shipping_fee', 'discount', 'suggestedCoupons'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $coupon = \App\Models\Coupon::findValid($request->code);
        
        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value
        ]);

        return back()->with('success', 'Đã áp dụng mã giảm giá thành công!');
    }

    public function processOrder(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('discs.index')->with('error', 'Giỏ hàng trống!');
        }

        $subtotal = collect($cart)->sum('price');
        $shipping_fee = 30000;
        $discount = 0;
        $couponCode = null;

        if (Session::has('coupon')) {
            $coupon = \App\Models\Coupon::findValid(Session::get('coupon')['code']);
            if ($coupon) {
                $discount = $coupon->calculateDiscount($subtotal);
                $couponCode = $coupon->code;
                $coupon->increment('used_count');
            }
        }

        $total_amount = $subtotal + $shipping_fee - $discount;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($cart as $item) {
                DiscOrder::create([
                    'disc_id' => $item['disc_id'],
                    'user_id' => auth()->id(),
                    'receiver_name' => $request->receiver_name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'status' => 'pending',
                    'shipping_fee' => $shipping_fee / count($cart), // Chia nhỏ phí ship cho từng món
                    'coupon_code' => $couponCode,
                    'discount_amount' => $discount / count($cart),
                    'total_amount' => ($item['price'] + ($shipping_fee / count($cart)) - ($discount / count($cart))),
                ]);
            }

            // Gửi Email thông báo đơn hàng
            try {
                \Illuminate\Support\Facades\Mail::to(auth()->user()->email)->send(new \App\Mail\OrderConfirmed(
                    $cart, 
                    $request->receiver_name, 
                    $request->phone, 
                    $request->address
                ));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi gửi mail đơn hàng: ' . $e->getMessage());
            }

            \Illuminate\Support\Facades\DB::commit();
            Session::forget(['cart', 'coupon']);
            return redirect()->route('discs.index')->with('success', 'Đơn hàng của bạn đã được gửi thành công!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
