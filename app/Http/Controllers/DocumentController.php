<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Auth::user()->documents()->latest()->get();
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:resume,certificate,id,medical,other',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $file = $request->file('document');
        $filePath = $file->store('documents/' . Auth::id(), 'public');

        Document::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'name' => $request->name,
            'file_path' => $filePath,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'description' => $request->description,
        ]);

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully.');
    }

    public function show(Document $document)
    {
        // Ensure the document belongs to the current user or is accessible by admin
        if ($document->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if file is in private or public storage
        if (str_starts_with($document->file_path, 'employer-verification/')) {
            // File is in private storage
            $filePath = storage_path('app/private/' . $document->file_path);
        } else {
            // File is in public storage
            $filePath = storage_path('app/public/' . $document->file_path);
        }
        
        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->file($filePath);
    }

    public function download(Document $document)
    {
        // Ensure the document belongs to the current user or is accessible by admin
        if ($document->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if file is in private or public storage
        if (str_starts_with($document->file_path, 'employer-verification/')) {
            // File is in private storage
            $filePath = storage_path('app/private/' . $document->file_path);
        } else {
            // File is in public storage
            $filePath = storage_path('app/public/' . $document->file_path);
        }

        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Get the original file extension and create download filename
        $fileExtension = pathinfo($document->file_path, PATHINFO_EXTENSION);
        $downloadFileName = $document->name . '.' . $fileExtension;

        // Return file download response
        return response()->download($filePath, $downloadFileName);
    }

    public function destroy(Document $document)
    {
        // Ensure the document belongs to the current user or is admin
        if ($document->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Check if file is in private or public storage and delete accordingly
            if (str_starts_with($document->file_path, 'employer-verification/')) {
                // File is in private storage - use 'local' disk
                Storage::disk('local')->delete($document->file_path);
            } else {
                // File is in public storage
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
            
        } catch (\Exception $e) {
            return redirect()->route('documents.index')->with('error', 'Error deleting document: ' . $e->getMessage());
        }
    }
}
