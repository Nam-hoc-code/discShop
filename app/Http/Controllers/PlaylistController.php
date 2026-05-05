<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Playlist;
use App\Models\Song;
use App\Models\Favorite;

class PlaylistController extends Controller
{
    private function autoFavorite($songId)
    {
        $userId = auth()->id();
        $exists = Favorite::where('user_id', $userId)->where('song_id', $songId)->exists();
        if (!$exists) {
            Favorite::create([
                'user_id' => $userId,
                'song_id' => $songId,
            ]);
        }
    }

    public function index()
    {
        $playlists = Playlist::where('user_id', auth()->id())
            ->withCount('songs')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('playlists.index', compact('playlists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $playlist = Playlist::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Nếu có yêu cầu tự động thêm bài hát vào playlist mới tạo
        if ($request->has('auto_add_song_id')) {
            $playlist->songs()->attach($request->auto_add_song_id);
            $this->autoFavorite($request->auto_add_song_id);
            return back()->with('success', "Đã tạo playlist '{$request->name}' và thêm bài hát thành công!");
        }

        return back()->with('success', 'Đã tạo danh sách phát mới!');
    }

    public function show($id)
    {
        $playlist = Playlist::where('user_id', auth()->id())
            ->with('songs.artist')
            ->findOrFail($id);
        return view('playlists.show', compact('playlist'));
    }

    public function update(Request $request, $id)
    {
        $playlist = Playlist::where('user_id', auth()->id())->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $playlist->update($request->only('name', 'description'));

        return back()->with('success', 'Đã cập nhật danh sách phát!');
    }

    public function destroy($id)
    {
        $playlist = Playlist::where('user_id', auth()->id())->findOrFail($id);
        $playlist->delete();

        return redirect()->route('playlists.index')->with('success', 'Đã xóa danh sách phát!');
    }

    public function addSong(Request $request)
    {
        $playlist = Playlist::where('user_id', auth()->id())->findOrFail($request->playlist_id);
        
        // Check if song already in playlist
        if (!$playlist->songs()->where('song_id', $request->song_id)->exists()) {
            $playlist->songs()->attach($request->song_id);
            $this->autoFavorite($request->song_id);
            return back()->with('success', 'Đã thêm bài hát vào danh sách phát và danh sách yêu thích!');
        }

        return back()->with('error', 'Bài hát đã có trong danh sách phát này!');
    }

    public function removeSong(Request $request, $playlistId)
    {
        $playlist = Playlist::where('user_id', auth()->id())->findOrFail($playlistId);
        $playlist->songs()->detach($request->song_id);

        return back()->with('success', 'Đã xóa bài hát khỏi danh sách phát!');
    }
}
