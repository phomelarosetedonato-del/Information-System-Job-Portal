<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ResumeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $resumes = $user->resumes()->latest()->get();

        return view('resumes.index', compact('resumes'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->resumes()->count() >= 1) {
            return redirect()->route('resumes.edit', $user->resumes()->first())
                ->with('info', 'You already have a resume. Edit it here.');
        }

        $defaultData = [
            'email_address' => $user->email,
            'mobile_number' => $user->phone ?? '',
            'complete_address' => $user->address ?? '',
        ];

        if ($user->pwdProfile) {
            $defaultData['first_name'] = $user->pwdProfile->first_name ?? '';
            $defaultData['surname'] = $user->pwdProfile->last_name ?? '';
            $defaultData['middle_name'] = $user->pwdProfile->middle_name ?? '';
            $defaultData['date_of_birth'] = $user->pwdProfile->birthdate ?? '';
            $defaultData['sex'] = $user->pwdProfile->gender ?? '';
            $defaultData['province'] = $user->pwdProfile->province ?? '';
        }

        return view('resumes.create', compact('defaultData'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateResume($request);

        DB::beginTransaction();
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($request->hasFile('profile_photo')) {
                $validated['profile_photo'] = $request->file('profile_photo')
                    ->store('resumes/photos', 'public');
            }

            if ($request->hasFile('personal_documents')) {
                $documents = [];
                foreach ($request->file('personal_documents') as $file) {
                    $documents[] = $file->store('resumes/documents', 'public');
                }
                $validated['personal_documents'] = $documents;
            }

            if ($request->hasFile('supporting_documents')) {
                $documents = [];
                foreach ($request->file('supporting_documents') as $file) {
                    $documents[] = $file->store('resumes/supporting', 'public');
                }
                $validated['supporting_documents'] = $documents;
            }

            $resume = $user->resumes()->create($validated);
            $resume->updateCompletionStatus();

            DB::commit();

            return redirect()->route('resumes.show', $resume)
                ->with('success', 'Resume created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create resume: ' . $e->getMessage());
        }
    }

    public function show(Resume $resume)
    {
        if ($resume->user_id !== Auth::id() && !$resume->is_published) {
            abort(403, 'Unauthorized access to this resume.');
        }

        $isOwner = $resume->user_id === Auth::id();

        if (!$isOwner) {
            $resume->incrementViews();
        }

        return view('resumes.show', compact('resume', 'isOwner'));
    }

    public function edit(Resume $resume)
    {
        if ($resume->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('resumes.edit', compact('resume'));
    }

    public function update(Request $request, Resume $resume)
    {
        if ($resume->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $this->validateResume($request, $resume->id);

        DB::beginTransaction();
        try {
            if ($request->hasFile('profile_photo')) {
                if ($resume->profile_photo) {
                    Storage::disk('public')->delete($resume->profile_photo);
                }
                $validated['profile_photo'] = $request->file('profile_photo')
                    ->store('resumes/photos', 'public');
            }

            if ($request->hasFile('personal_documents')) {
                $documents = $resume->personal_documents ?? [];
                foreach ($request->file('personal_documents') as $file) {
                    $documents[] = $file->store('resumes/documents', 'public');
                }
                $validated['personal_documents'] = $documents;
            }

            if ($request->hasFile('supporting_documents')) {
                $documents = $resume->supporting_documents ?? [];
                foreach ($request->file('supporting_documents') as $file) {
                    $documents[] = $file->store('resumes/supporting', 'public');
                }
                $validated['supporting_documents'] = $documents;
            }

            $resume->update($validated);
            $resume->updateCompletionStatus();

            DB::commit();

            return redirect()->route('resumes.show', $resume)
                ->with('success', 'Resume updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update resume: ' . $e->getMessage());
        }
    }

    public function destroy(Resume $resume)
    {
        if ($resume->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        try {
            if ($resume->profile_photo) {
                Storage::disk('public')->delete($resume->profile_photo);
            }

            if ($resume->personal_documents) {
                foreach ($resume->personal_documents as $doc) {
                    Storage::disk('public')->delete($doc);
                }
            }

            if ($resume->supporting_documents) {
                foreach ($resume->supporting_documents as $doc) {
                    Storage::disk('public')->delete($doc);
                }
            }

            $resume->delete();

            return redirect()->route('resumes.index')
                ->with('success', 'Resume deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete resume: ' . $e->getMessage());
        }
    }

    public function togglePublish(Resume $resume)
    {
        if ($resume->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        if ($resume->is_published) {
            $resume->unpublish();
            $message = 'Resume unpublished successfully!';
        } else {
            if ($resume->publish()) {
                $message = 'Resume published successfully!';
            } else {
                return back()->with('error', 'Resume must be at least 80% complete to publish.');
            }
        }

        return back()->with('success', $message);
    }

    public function deleteDocument(Request $request, Resume $resume)
    {
        if ($resume->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $type = $request->input('type');
        $index = $request->input('index');

        if (!in_array($type, ['personal_documents', 'supporting_documents'])) {
            return response()->json(['error' => 'Invalid document type'], 400);
        }

        $documents = $resume->$type ?? [];

        if (isset($documents[$index])) {
            Storage::disk('public')->delete($documents[$index]);
            unset($documents[$index]);
            $documents = array_values($documents);
            $resume->update([$type => $documents]);

            return response()->json(['success' => true, 'message' => 'Document deleted successfully']);
        }

        return response()->json(['error' => 'Document not found'], 404);
    }

    public function download(Resume $resume)
    {
        if ($resume->user_id !== Auth::id() && !$resume->is_published) {
            abort(403, 'Unauthorized access.');
        }

        // TODO: Implement PDF generation using DomPDF or similar
        return back()->with('info', 'PDF download feature coming soon!');
    }

    private function validateResume(Request $request, $resumeId = null)
    {
        return $request->validate([
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'sex' => 'required|in:male,female,prefer_not_to_say',
            'mobile_number' => 'required|string|max:20',
            'email_address' => 'required|email|max:255',
            'province' => 'required|string|max:255',
            'complete_address' => 'nullable|string|max:500',
            'professional_summary' => 'nullable|string|max:1000',
            'career_objective' => 'nullable|string|max:1000',
            'educational_attainment' => 'required|string|max:255',
            'course' => 'nullable|string|max:255',
            'school_name' => 'nullable|string|max:255',
            'school_address' => 'nullable|string|max:500',
            'year_graduated' => 'nullable|integer|min:1950|max:' . (date('Y') + 10),
            'additional_education' => 'nullable|array',
            'eligibility' => 'nullable|array',
            'work_experience' => 'nullable|array',
            'trainings' => 'nullable|array',
            'skills' => 'nullable|array',
            'languages' => 'nullable|array',
            'profile_photo' => 'nullable|image|max:2048',
            'personal_documents.*' => 'nullable|file|mimes:pdf|max:5120',
            'supporting_documents.*' => 'nullable|file|mimes:pdf|max:5120',
            'application_letter' => 'nullable|string|max:5000',
            'is_published' => 'nullable|boolean',
            'is_searchable' => 'nullable|boolean',
            'visibility' => 'nullable|in:private,employers_only,public',
            'template' => 'nullable|string|in:professional,modern,classic,creative',
        ]);
    }
}
