<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Favorite;
use App\Models\Song;

use App\Models\FavoriteDisc;
use App\Models\Disc;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with(['song.artist'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $favoriteDiscs = FavoriteDisc::with(['disc.songs.artist', 'disc.genre'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('favorites.index', compact('favorites', 'favoriteDiscs'));
    }

    public function toggle(Request $request)
    {
        $song_id = $request->song_id;
        $user_id = auth()->id();

        $favorite = Favorite::where('user_id', $user_id)
            ->where('song_id', $song_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
        } else {
            Favorite::create([
                'user_id' => $user_id,
                'song_id' => $song_id,
            ]);
            $status = 'added';
        }

        if ($request->ajax()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $status === 'added' ? 'Đã thêm vào yêu thích!' : 'Đã xóa khỏi yêu thích!');
    }

    public function toggleDisc(Request $request)
    {
        $disc_id = $request->disc_id;
        $user_id = auth()->id();

        $favorite = FavoriteDisc::where('user_id', $user_id)
            ->where('disc_id', $disc_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
        } else {
            FavoriteDisc::create([
                'user_id' => $user_id,
                'disc_id' => $disc_id,
            ]);
            $status = 'added';
        }

        if ($request->ajax()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $status === 'added' ? 'Đã thêm đĩa nhạc vào yêu thích!' : 'Đã xóa đĩa nhạc khỏi yêu thích!');
    }

    public function remove($id)
    {
        $favorite = Favorite::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();
            
        $favorite->delete();

        return back()->with('success', 'Đã xóa khỏi danh sách yêu thích!');
    }

    public function removeDisc($id)
    {
        $favorite = FavoriteDisc::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();
            
        $favorite->delete();

        return back()->with('success', 'Đã xóa đĩa nhạc khỏi danh sách yêu thích!');
    }
}
