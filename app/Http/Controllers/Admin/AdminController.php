<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Song;
use App\Models\ActivityLog;

class AdminController extends Controller
{
    private function logAction($action, $targetType, $targetId, $description, $data = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
            'data' => $data,
            'ip_address' => request()->ip(),
        ]);
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalSongs = Song::count();
        $pendingSongs = Song::where('status', 'PENDING')->count();

        return view('admin.dashboard', compact('totalUsers', 'totalSongs', 'pendingSongs'));
    }

    public function songRequests()
    {
        $requests = Song::with('artist')
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.songs.requests', compact('requests'));
    }

    public function approveSong($id)
    {
        $song = Song::with('artist.followers')->findOrFail($id);
        $song->update(['status' => 'APPROVED']);

        $this->logAction('DUYỆT', 'SONG', $song->id, "Đã duyệt bài hát: {$song->title}", ['old_status' => 'PENDING']);

        // Gửi thông báo cho những người đang theo dõi ca sĩ này
        $artist = $song->artist;
        foreach ($artist->followers as $follower) {
            \App\Models\Notification::create([
                'user_id' => $follower->id,
                'title' => 'Bài hát mới từ ' . $artist->username,
                'message' => "Nghệ sĩ bạn quan tâm vừa ra mắt bài hát mới: {$song->title}",
                'link' => route('home', ['song_id' => $song->id]),
            ]);
        }

        return redirect()->route('admin.songs.requests')->with('success', "Đã duyệt bài hát: {$song->title}");
    }

    public function rejectSong($id)
    {
        $song = Song::findOrFail($id);
        $song->update(['status' => 'REJECTED']);

        $this->logAction('TỪ CHỐI', 'SONG', $song->id, "Đã từ chối bài hát: {$song->title}", ['old_status' => 'PENDING']);

        return redirect()->route('admin.songs.requests')->with('success', "Đã từ chối bài hát: {$song->title}");
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        $this->logAction('SỬA QUYỀN', 'USER', $user->id, "Thay đổi quyền từ {$oldRole} sang {$request->role} cho user: {$user->username}", ['old_role' => $oldRole]);

        return back()->with('success', "Đã cập nhật quyền cho {$user->username}");
    }

    public function updateUserStatus($id)
    {
        $user = User::findOrFail($id);
        $oldStatus = $user->status;
        $newStatus = $oldStatus === 'ACTIVE' ? 'LOCKED' : 'ACTIVE';
        $user->update(['status' => $newStatus]);

        $action = $newStatus === 'LOCKED' ? 'KHÓA' : 'MỞ KHÓA';
        $this->logAction($action, 'USER', $user->id, "{$action} tài khoản user: {$user->username}", ['old_status' => $oldStatus]);

        return back()->with('success', "Đã {$action} tài khoản {$user->username}");
    }

    public function logs()
    {
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.logs.index', compact('logs'));
    }

    public function undoAction($id)
    {
        $log = ActivityLog::findOrFail($id);
        
        if (!$log->data || !isset($log->data)) {
            return back()->withErrors(['error' => 'Không thể hoàn tác hành động này (thiếu dữ liệu gốc).']);
        }

        $target = null;
        if ($log->target_type === 'USER') {
            $target = User::find($log->target_id);
        } elseif ($log->target_type === 'SONG') {
            $target = Song::find($log->target_id);
        }

        if (!$target) {
            return back()->withErrors(['error' => 'Đối tượng đích không còn tồn tại để hoàn tác.']);
        }

        $undoData = $log->data;
        $description = "HOÀN TÁC: ";

        if (isset($undoData['old_status'])) {
            $target->update(['status' => $undoData['old_status']]);
            $description .= "Khôi phục trạng thái thành {$undoData['old_status']}";
        } elseif (isset($undoData['old_role'])) {
            $target->update(['role' => $undoData['old_role']]);
            $description .= "Khôi phục quyền thành {$undoData['old_role']}";
        }

        $this->logAction('HOÀN TÁC', $log->target_type, $log->target_id, $description . " cho {$log->target_type} #{$log->target_id}");

        // Đánh dấu log này đã được hoàn tác bằng cách xóa data
        $log->update(['data' => null]); 

        return back()->with('success', 'Đã hoàn tác hành động thành công!');
    }
}
