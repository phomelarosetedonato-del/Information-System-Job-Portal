<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = auth()->user()->documents()->latest()->get();
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
        $filePath = $file->store('documents/' . auth()->id(), 'public');

        Document::create([
            'user_id' => auth()->id(),
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
        // Ensure the document belongs to the current user
        if ($document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->file(storage_path('app/public/' . $document->file_path));
    }

    public function destroy(Document $document)
    {
        // Ensure the document belongs to the current user
        if ($document->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete file from storage
        Storage::disk('public')->delete($document->file_path);

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
