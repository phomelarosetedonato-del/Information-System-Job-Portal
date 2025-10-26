<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return redirect()->back()->with('success', 'Notification marked as read.');
        }

        return redirect()->back()->with('error', 'Notification not found.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Remove the specified notification.
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->delete();
            return redirect()->back()->with('success', 'Notification deleted.');
        }

        return redirect()->back()->with('error', 'Notification not found.');
    }

    /**
     * Clear all notifications.
     */
    public function clearAll()
    {
        Auth::user()->notifications()->delete();

        return redirect()->route('notifications.index')->with('success', 'All notifications cleared.');
    }
}
