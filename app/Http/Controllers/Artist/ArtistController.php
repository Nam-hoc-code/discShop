<?php

namespace App\Http\Controllers\Artist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Song;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

use App\Models\Disc;
use App\Models\DiscOrder;

class ArtistController extends Controller
{
    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key'    => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    public function dashboard()
    {
        $artist_id = auth()->id();
        $totalSongs = Song::where('artist_id', $artist_id)->where('is_deleted', false)->count();
        $pendingSongs = Song::where('artist_id', $artist_id)->where('status', 'PENDING')->where('is_deleted', false)->count();
        $totalOrders = DiscOrder::whereHas('disc.songs', function($q) use ($artist_id) {
            $q->where('artist_id', $artist_id);
        })->count();

        return view('artist.dashboard', compact('totalSongs', 'pendingSongs', 'totalOrders'));
    }

    public function songs()
    {
        $songs = Song::where('artist_id', auth()->id())
            ->where('is_deleted', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('artist.songs.index', compact('songs'));
    }

    public function createSong()
    {
        $genres = \App\Models\Genre::all();
        return view('artist.songs.create', compact('genres'));
    }

    public function storeSong(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'audio' => 'required|mimes:mp3|max:10240', // 10MB limit
            'cover' => 'required|image|max:2048', // 2MB limit
            'genre_id' => 'nullable|exists:genres,id',
            'new_genre' => 'nullable|string|max:50',
        ]);

        try {
            $audioFile = $request->file('audio');
            $coverFile = $request->file('cover');

            $uploadApi = new UploadApi();

            // Xử lý Thể loại nhạc
            $genreId = $request->genre_id;
            if ($request->filled('new_genre')) {
                $genre = \App\Models\Genre::firstOrCreate(
                    ['name' => $request->new_genre],
                    ['slug' => \Illuminate\Support\Str::slug($request->new_genre)]
                );
                $genreId = $genre->id;
            }

            // Upload Audio
            $audioResult = $uploadApi->upload($audioFile->getRealPath(), [
                'resource_type' => 'video',
                'folder' => 'music/audio'
            ]);

            // Upload Cover
            $coverResult = $uploadApi->upload($coverFile->getRealPath(), [
                'folder' => 'music/cover'
            ]);

            Song::create([
                'title' => $request->title,
                'artist_id' => auth()->id(),
                'genre_id' => $genreId,
                'cover_image' => $coverResult['secure_url'],
                'cloud_url' => $audioResult['secure_url'],
                'cloud_public_id' => $audioResult['public_id'],
                'status' => 'PENDING',
                'is_deleted' => false,
            ]);

            return redirect()->route('artist.songs')->with('success', 'Bài hát đã được tải lên và đang chờ duyệt.');
        } catch (\Exception $e) {
            return back()->withErrors(['audio' => 'Lỗi tải lên Cloudinary: ' . $e->getMessage()])->withInput();
        }
    }

    public function deleteSong($id)
    {
        $song = Song::where('artist_id', auth()->id())->findOrFail($id);
        
        // Destroy on Cloudinary if exists
        if ($song->cloud_public_id) {
            $uploadApi = new UploadApi();
            $uploadApi->destroy($song->cloud_public_id, [
                'resource_type' => 'video'
            ]);
        }

        $song->update(['is_deleted' => true]);

        return redirect()->route('artist.songs')->with('success', 'Đã xóa bài hát.');
    }

    public function orders()
    {
        $artist_id = auth()->id();
        $query = DiscOrder::with(['user', 'disc.songs'])
            ->whereHas('disc.songs', function($q) use ($artist_id) {
                $q->where('artist_id', $artist_id);
            })
            ->orderBy('created_at', 'desc');

        $activeOrders = (clone $query)->whereIn('status', ['pending', 'confirmed', 'shipping'])->get();
        $orderHistory = (clone $query)->whereIn('status', ['done', 'cancelled'])->get();

        return view('artist.orders.index', compact('activeOrders', 'orderHistory'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $artist_id = auth()->id();
        $order = DiscOrder::whereHas('disc.songs', function($q) use ($artist_id) {
                $q->where('artist_id', $artist_id);
            })->findOrFail($id);

        $request->validate(['status' => 'required|string']);
        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Gửi Email thông báo trạng thái mới cho người mua
        try {
            if ($order->user && $order->user->email) {
                \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Lỗi gửi mail cập nhật trạng thái đơn hàng: ' . $e->getMessage());
        }

        // Ghi nhật ký hoạt động (Log)
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_ORDER_STATUS',
            'target_type' => 'ORDER',
            'target_id' => $order->id,
            'description' => "Đã cập nhật trạng thái đơn hàng #{$order->id} từ '{$oldStatus}' sang '{$request->status}'",
            'data' => json_encode([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'customer' => $order->user->username
            ])
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }

    public function discs()
    {
        $artist_id = auth()->id();
        $discs = Disc::with(['songs', 'genre'])
            ->whereHas('songs', function($q) use ($artist_id) {
                $q->where('artist_id', $artist_id);
            })
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->get();

        $songs = Song::where('artist_id', $artist_id)
            ->approved()
            ->where('is_deleted', false)
            ->get();

        $genres = \App\Models\Genre::all();

        return view('artist.discs.index', compact('discs', 'songs', 'genres'));
    }

    public function storeDisc(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'song_ids' => 'required|array|min:1',
            'song_ids.*' => 'exists:songs,id',
            'price' => 'required|numeric|min:1000',
            'first_song_id' => 'required|exists:songs,id',
        ]);

        $disc = Disc::create([
            'title' => $request->title,
            'artist_id' => auth()->id(),
            'genre_id' => $request->genre_id,
            'price' => $request->price
        ]);

        // Gắn các bài hát và vị trí
        $syncData = [];
        $syncData[$request->first_song_id] = ['position' => 1];
        
        $pos = 2;
        foreach ($request->song_ids as $songId) {
            if ($songId != $request->first_song_id) {
                $syncData[$songId] = ['position' => $pos++];
            }
        }
        
        $disc->songs()->sync($syncData);
        
        $firstSong = \App\Models\Song::find($request->first_song_id);
        $songCount = count($request->song_ids);
        $title = $songCount > 1 ? "'{$firstSong->title}' và " . ($songCount - 1) . " bài hát khác" : "'{$firstSong->title}'";

        // Gửi thông báo cho TẤT CẢ người dùng về đĩa mới
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Đĩa nhạc mới vừa ra mắt!',
                'message' => "Đĩa nhạc chứa bài hát {$title} đã sẵn sàng để đặt mua.",
                'link' => route('discs.show', $disc->id),
            ]);
        }

        return back()->with('success', 'Đã thêm đĩa nhạc mới với ' . $songCount . ' bài hát.');
    }

    public function deleteDisc($id)
    {
        $artist_id = auth()->id();
        $disc = Disc::whereHas('songs', function($q) use ($artist_id) {
                $q->where('artist_id', $artist_id);
            })->withCount('orders')->findOrFail($id);

        if ($disc->orders_count > 0) {
            return back()->with('error', 'Không thể xóa đĩa đã có đơn hàng.');
        }

        $disc->delete();
        return back()->with('success', 'Đã xóa đĩa nhạc.');
    }
}
