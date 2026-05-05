<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ người dùng.
     */
    public function index(Request $request)
    {
        // Lấy danh sách bài hát cho sidebar
        $songList = Song::with('artist')->approved()->latest()->get();

        // Lấy bài hát đang phát (nếu có song_id trong query)
        $currentSong = null;
        if ($request->has('song_id')) {
            $currentSong = Song::with('artist')->find($request->song_id);
            if ($currentSong) {
                session(['current_song' => $currentSong]);
            }
        } else {
            $currentSong = session('current_song');
        }

        // Đảm bảo currentSong luôn là object để tránh lỗi "Attempt to read property on array"
        if ($currentSong && is_array($currentSong)) {
            $currentSong = (object) $currentSong;
        }
        if ($currentSong && isset($currentSong->artist) && is_array($currentSong->artist)) {
            $currentSong->artist = (object) $currentSong->artist;
        }

        // Lấy bài hát thịnh hành (ví dụ: 5 bài mới nhất)
        $trendingSongs = Song::with('artist')->approved()->latest()->limit(5)->get();

        // Lấy nghệ sĩ phổ biến (ví dụ: 5 nghệ sĩ có nhiều bài hát nhất)
        $popularArtists = User::where('role', 'ARTIST')
            ->withCount(['songs' => function ($query) {
                $query->where('status', 'APPROVED');
            }])
            ->orderBy('songs_count', 'desc')
            ->limit(5)
            ->get();

        // Lấy danh sách phát của người dùng
        $userPlaylists = auth()->check() ? auth()->user()->playlists : collect();

        // Lấy sự kiện âm nhạc (3 sự kiện mới nhất)
        $events = \App\Models\Event::orderBy('event_date', 'asc')
            ->where('event_date', '>=', now())
            ->limit(3)
            ->get();

        // Lấy 5 thông báo mới nhất cho chuông thông báo
        $headerNotifications = auth()->check() 
            ? auth()->user()->notifications()->latest()->limit(5)->get() 
            : collect();
        $unreadCount = auth()->check() 
            ? auth()->user()->notifications()->where('is_read', false)->count() 
            : 0;

        // Lấy ID của tất cả bài hát đã có trong các Playlist của User để hiện dấu tích
        $userPlaylistSongIds = auth()->check()
            ? DB::table('playlist_song')
                ->whereIn('playlist_id', $userPlaylists->pluck('id'))
                ->pluck('song_id')
                ->toArray()
            : [];

        return view('user.home', compact('songList', 'currentSong', 'trendingSongs', 'popularArtists', 'userPlaylists', 'events', 'headerNotifications', 'unreadCount', 'userPlaylistSongIds'));
    }

    public function artistProfile($id)
    {
        $artist = User::where('role', 'ARTIST')
            ->with(['songs' => function($query) {
                $query->where('status', 'APPROVED')->latest();
            }, 'discs.songs'])
            ->withCount('followers')
            ->findOrFail($id);

        return view('user.artist_profile', compact('artist'));
    }

    public function toggleFollow($id)
    {
        $artist = User::where('role', 'ARTIST')->findOrFail($id);
        $user = auth()->user();

        if ($user->followings()->where('artist_id', $id)->exists()) {
            $user->followings()->detach($id);
            $message = "Đã hủy theo dõi {$artist->username}";
        } else {
            $user->followings()->attach($id);
            $message = "Đã theo dõi {$artist->username}. Bạn sẽ nhận được thông báo khi ca sĩ này ra bài hát mới!";
        }

        return back()->with('success', $message);
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back();
    }

    public function allNotifications()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);
        return view('user.notifications', compact('notifications'));
    }
}
