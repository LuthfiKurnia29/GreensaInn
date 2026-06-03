<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Ambil semua notifikasi milik user yang sedang login.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($notif) {
                return [
                    'id'             => $notif->id,
                    'type'           => $notif->type,
                    'message'        => $notif->message,
                    'is_read'        => $notif->is_read,
                    'peminjaman_id'  => $notif->peminjaman_id,
                    'created_at'     => $notif->created_at->diffForHumans(),
                ];
            });

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markRead($id)
    {
        $notif = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notifikasi user sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
