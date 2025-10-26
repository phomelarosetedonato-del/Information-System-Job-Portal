<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // FIX: Use Auth facade with type hinting
        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

        Announcement::create($validated);

        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully.');
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

        $validated['is_active'] = $request->has('is_active');

        $announcement->update($validated);

        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully.');
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
