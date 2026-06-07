<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;

class ContactMessageController extends Controller
{
    /**
     * Display user's contact messages
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Contact::where('user_id', $user->id)
                        ->orWhere('email', $user->email)
                        ->latest();

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'unanswered') {
                $query->whereNull('responded_at');
            } elseif ($request->status === 'answered') {
                $query->whereNotNull('responded_at');
            }
        }

        $messages = $query->paginate(10);
        $totalMessages = Contact::where('user_id', $user->id)
                               ->orWhere('email', $user->email)
                               ->count();
        $answeredCount = Contact::where('user_id', $user->id)
                               ->orWhere('email', $user->email)
                               ->whereNotNull('responded_at')
                               ->count();
        $unansweredCount = $totalMessages - $answeredCount;

        return view('contact-messages.index', compact(
            'messages',
            'totalMessages',
            'answeredCount',
            'unansweredCount'
        ));
    }

    /**
     * Display single contact message with response
     */
    public function show(Contact $contact)
    {
        $user = Auth::user();

        // Check if user owns this message
        if ($contact->user_id !== $user->id && $contact->email !== $user->email) {
            abort(403, 'Unauthorized access.');
        }

        return view('contact-messages.show', compact('contact'));
    }

    /**
     * Mark message as read by user
     */
    public function markRead(Contact $contact)
    {
        $user = Auth::user();

        if ($contact->user_id !== $user->id && $contact->email !== $user->email) {
            abort(403, 'Unauthorized access.');
        }
        // Mark notification as read if exists
        $user->notifications()
             ->where('data->contact_id', $contact->id)
             ->update(['read_at' => now()]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Message marked as read.');
    }
}
