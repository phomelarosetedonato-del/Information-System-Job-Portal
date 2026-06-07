<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index(Request $request)
    {
        Auth::user()->recordAdminAction();

        $query = Contact::latest();

        // Filter by read status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'responded') {
                $query->responded();
            } elseif ($request->status === 'unresponded') {
                $query->unresponded();
            }
        }

        // Search by email or name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%");
            });
        }

        // Filter by inquiry type
        if ($request->has('inquiry_type') && $request->inquiry_type) {
            $query->where('inquiry_type', $request->inquiry_type);
        }

        $contacts = $query->paginate(20);
        $unreadCount = Contact::getUnreadCount();
        $unrespondedCount = Contact::getUnrespondedCount();
        $inquiryTypes = [
            'job_application_support' => 'Job Application Support',
            'employer_partnership' => 'Employer Partnership',
            'training_programs' => 'Training Programs',
            'technical_support' => 'Technical Support',
            'accessibility_concerns' => 'Accessibility Concerns',
            'account_issues' => 'Account Issues',
            'feedback' => 'Feedback & Suggestions',
            'other' => 'Other',
        ];

        return view('admin.contacts.index', compact(
            'contacts',
            'unreadCount',
            'unrespondedCount',
            'inquiryTypes'
        ));
    }

    /**
     * Display a specific contact message
     */
    public function show(Contact $contact)
    {
        Auth::user()->recordAdminAction();

        // Mark as read
        if (!$contact->is_read) {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mark contact as read
     */
    public function markRead(Contact $contact)
    {
        Auth::user()->recordAdminAction();

        $contact->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Message marked as read.');
    }

    /**
     * Mark contact as unread
     */
    public function markUnread(Contact $contact)
    {
        Auth::user()->recordAdminAction();

        $contact->markAsUnread();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Message marked as unread.');
    }

    /**
     * Mark contact as responded
     */
    public function respond(Request $request, Contact $contact)
    {
        Auth::user()->recordAdminAction();

        $validated = $request->validate([
            'response_notes' => 'required|string|min:10',
        ]);

        $contact->markAsResponded($validated['response_notes']);

        // Send response notification to user
        try {
            $contact->sendResponseNotification();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send contact response notification', [
                'error' => $e->getMessage(),
                'contact_id' => $contact->id
            ]);
        }

        return redirect()->back()->with('success', 'Message marked as responded and user notified.');
    }

    /**
     * Delete a contact message
     */
    public function destroy(Contact $contact)
    {
        Auth::user()->recordAdminAction();

        $contact->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.contacts.index')->with('success', 'Contact message deleted.');
    }

    /**
     * Export contacts to CSV
     */
    public function export(Request $request)
    {
        Auth::user()->recordAdminAction();

        $filename = 'contact-messages-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $query = Contact::latest();

        // Apply filters
        if ($request->has('status') && $request->status) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'responded') {
                $query->where('responded_at', '!=', null);
            }
        }

        $contacts = $query->get();

        $callback = function() use ($contacts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Inquiry Type', 'Subject', 'Message', 'Status', 'Responded', 'Date Submitted', 'Response Notes']);

            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->name,
                    $contact->email,
                    $contact->inquiry_type_display,
                    $contact->subject,
                    $contact->message,
                    $contact->is_read ? 'Read' : 'Unread',
                    $contact->responded_at ? 'Yes' : 'No',
                    $contact->created_at->format('Y-m-d H:i:s'),
                    $contact->response_notes ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
