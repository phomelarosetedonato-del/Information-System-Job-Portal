<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        Log::info('═════════════════════════════════════════════');
        Log::info('AnnouncementController@store - REQUEST START', [
            'method' => $request->method(),
            'path' => $request->path(),
            'user_id' => Auth::id(),
            'user_authenticated' => Auth::check() ? 'YES' : 'NO',
            'user_role' => Auth::user()?->role ?? 'none',
            'user_is_admin' => Auth::check() && Auth::user()->isAdmin() ? 'YES' : 'NO',
            'timestamp' => now()->toDateTimeString(),
        ]);

        try {
            // STEP 1: Validate input
            Log::info('AnnouncementController@store - STEP 1: Validating input');
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);
            Log::info('AnnouncementController@store - STEP 1: Validation PASSED', [
                'title_length' => strlen($validated['title']),
                'content_length' => strlen($validated['content']),
            ]);

            // STEP 2: Prepare data
            Log::info('AnnouncementController@store - STEP 2: Preparing data');
            $user_id = Auth::id();
            if (!$user_id) {
                throw new \Exception('User ID is null - authentication issue');
            }

            $validated['created_by'] = $user_id;
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;

            Log::info('AnnouncementController@store - STEP 2: Data prepared', [
                'created_by' => $validated['created_by'],
                'is_active' => $validated['is_active'],
            ]);

            // STEP 3: Create announcement in database
            Log::info('AnnouncementController@store - STEP 3: Creating announcement in database');
            $announcement = Announcement::create($validated);

            if (!$announcement->id) {
                throw new \Exception('Failed to create announcement - no ID returned');
            }

            Log::info('AnnouncementController@store - STEP 3: Announcement created successfully', [
                'announcement_id' => $announcement->id,
                'announcement_title' => $announcement->title,
                'announcement_active' => $announcement->is_active,
                'created_at' => $announcement->created_at,
            ]);

            // STEP 4: Send notifications (wrapped in try-catch so it doesn't break the response)
            Log::info('AnnouncementController@store - STEP 4: Attempting to send notifications');
            if ($announcement->is_active) {
                try {
                    $this->notifyPwdUsers($announcement);
                    Log::info('AnnouncementController@store - STEP 4: Notifications sent successfully');
                } catch (\Throwable $notifyError) {
                    Log::error('AnnouncementController@store - STEP 4: Notification ERROR (but continuing)', [
                        'error_message' => $notifyError->getMessage(),
                        'error_code' => $notifyError->getCode(),
                        'announcement_id' => $announcement->id,
                    ]);
                    // Don't throw - let the response happen anyway
                }
            } else {
                Log::info('AnnouncementController@store - STEP 4: Skipped (announcement not active)');
            }

            // STEP 5: Prepare redirect response
            Log::info('AnnouncementController@store - STEP 5: Preparing redirect response');

            $redirectRoute = 'admin.announcements.index';
            $redirectUrl = route($redirectRoute);
            $successMessage = 'Announcement created successfully' . ($announcement->is_active ? ' and notifications sent to all PWD users.' : '.');

            Log::info('AnnouncementController@store - STEP 5: Redirect prepared', [
                'route' => $redirectRoute,
                'url' => $redirectUrl,
                'message' => $successMessage,
            ]);

            // STEP 6: Create and return redirect response
            Log::info('AnnouncementController@store - STEP 6: Returning redirect response');
            $response = redirect()->route($redirectRoute)->with('success', $successMessage);

            // Ensure session is saved before redirect
            if (method_exists($response, 'getSession')) {
                try {
                    $response->getSession()->save();
                    Log::info('AnnouncementController@store - STEP 6: Session saved explicitly');
                } catch (\Throwable $e) {
                    Log::warning('AnnouncementController@store - STEP 6: Could not save session explicitly', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('AnnouncementController@store - REQUEST SUCCESS', [
                'response_status' => $response->status(),
                'response_location' => $response->headers->get('Location'),
                'announcement_id' => $announcement->id,
                'timestamp' => now()->toDateTimeString(),
            ]);
            Log::info('═════════════════════════════════════════════');

            return $response;

        } catch (\Illuminate\Validation\ValidationException $validationError) {
            Log::warning('AnnouncementController@store - VALIDATION ERROR', [
                'errors' => $validationError->errors(),
                'timestamp' => now()->toDateTimeString(),
            ]);
            throw $validationError;

        } catch (\Throwable $fatalError) {
            Log::error('AnnouncementController@store - FATAL ERROR', [
                'error_class' => get_class($fatalError),
                'error_message' => $fatalError->getMessage(),
                'error_file' => $fatalError->getFile(),
                'error_line' => $fatalError->getLine(),
                'error_code' => $fatalError->getCode(),
                'user_id' => Auth::id(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            // Return error response
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create announcement: ' . $fatalError->getMessage()]);
        }
    }

    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $wasInactive = !$announcement->is_active;
        $validated['is_active'] = $request->has('is_active');

        $announcement->update($validated);

        // Notify PWD users if announcement just became active
        if ($wasInactive && $announcement->is_active) {
            $this->notifyPwdUsers($announcement);
        }

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted successfully.');
    }

    /**
     * Notify all PWD users about the announcement via email and dashboard
     */
    private function notifyPwdUsers(Announcement $announcement)
    {
        Log::info('AnnouncementController@notifyPwdUsers - START', [
            'announcement_id' => $announcement->id,
            'announcement_title' => $announcement->title,
            'timestamp' => now()->toDateTimeString(),
        ]);

        try {
            // Get all PWD users
            $pwdUsers = User::where('role', 'pwd')->where('is_active', true)->get();

            Log::info('AnnouncementController@notifyPwdUsers - PWD users fetched', [
                'count' => $pwdUsers->count(),
                'user_ids' => $pwdUsers->pluck('id')->toArray(),
            ]);

            // Send notifications in batch
            if ($pwdUsers->isNotEmpty()) {
                Log::info('AnnouncementController@notifyPwdUsers - Sending notifications', [
                    'user_count' => $pwdUsers->count(),
                    'announcement_id' => $announcement->id,
                    'timestamp' => now()->toDateTimeString(),
                ]);

                Notification::send($pwdUsers, new AnnouncementNotification($announcement));

                Log::info('AnnouncementController@notifyPwdUsers - Notifications sent successfully', [
                    'user_count' => $pwdUsers->count(),
                    'announcement_id' => $announcement->id,
                    'timestamp' => now()->toDateTimeString(),
                ]);
            } else {
                Log::warning('AnnouncementController@notifyPwdUsers - No active PWD users found', [
                    'announcement_id' => $announcement->id,
                ]);
            }

            Log::info('AnnouncementController@notifyPwdUsers - END', [
                'announcement_id' => $announcement->id,
                'timestamp' => now()->toDateTimeString(),
            ]);

        } catch (\Throwable $e) {
            Log::error('AnnouncementController@notifyPwdUsers - FATAL ERROR', [
                'announcement_id' => $announcement->id,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_class' => get_class($e),
                'timestamp' => now()->toDateTimeString(),
            ]);
            throw $e;
        }
    }

    // Add public routes for all users
    public function publicIndex()
    {
        $announcements = Announcement::where('is_active', true)->latest()->get();
        return view('announcements.public-index', compact('announcements'));
    }

    public function publicShow(Announcement $announcement)
    {
        if (!$announcement->is_active) {
            abort(404);
        }
        return view('announcements.public-show', compact('announcement'));
    }
}

