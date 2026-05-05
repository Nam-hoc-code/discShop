<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Song;
use App\Models\User;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('q');
        $songs = collect();
        $artists = collect();

        if ($keyword) {
            $songs = Song::with('artist')
                ->where('status', 'APPROVED')
                ->where(function($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%$keyword%")
                          ->orWhereHas('artist', function($q) use ($keyword) {
                              $q->where('username', 'LIKE', "%$keyword%");
                          });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $artists = User::where('role', 'ARTIST')
                ->where('username', 'LIKE', "%$keyword%")
                ->get();
        }

        return view('search.index', compact('songs', 'artists', 'keyword'));
    }
}
