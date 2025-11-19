<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PwdProfile;
use App\Models\DisabilityType;
use App\Models\SkillOption;
use App\Models\QualificationOption;
use App\Models\AccommodationOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user's profile
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if user exists (should always exist for authenticated users)
        if (!$user) {
            abort(404, 'User not found');
        }

        $pwdProfile = $user->pwdProfile;

        // If user is PWD but has no profile, redirect to complete it
        if ($user->isPwd() && !$pwdProfile) {
            return redirect()->route('profile.pwd-complete-form')
                ->with('error', 'Please complete your PWD profile to access all features.');
        }

        // Provide disability types for select inputs
        $disabilityTypes = DisabilityType::orderBy('type')->get();

        return view('profile.show', [
            'user' => $user,
            'pwdProfile' => $pwdProfile,
            'disabilityTypes' => $disabilityTypes,
        ]);
    }

    /**
     * Show the form for editing the user's profile
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(404, 'User not found');
        }

        $pwdProfile = $user->pwdProfile;

        // If PWD user has no profile, redirect to complete it first
        if ($user->isPwd() && !$pwdProfile) {
            return redirect()->route('profile.pwd-complete-form')
                ->with('error', 'Please complete your PWD profile before editing.');
        }

        $disabilityTypes = DisabilityType::orderBy('type')->get();
        $skillOptions = SkillOption::active()->orderBy('name')->get();
        $qualificationOptions = QualificationOption::active()->orderBy('name')->get();
        $accommodationOptions = AccommodationOption::active()->orderBy('name')->get();

        return view('profile.edit', [
            'user' => $user,
            'pwdProfile' => $pwdProfile,
            'disabilityTypes' => $disabilityTypes,
            'skillOptions' => $skillOptions,
            'qualificationOptions' => $qualificationOptions,
            'accommodationOptions' => $accommodationOptions,
        ]);
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Start with basic user validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            // Update user basic information
            $user->update($validated);

            // If user is PWD, update PWD profile fields
            if ($user->isPwd()) {
                $pwdValidated = $request->validate([
                    'disability_type_id' => 'required|integer|exists:disability_types,id',
                    'gender' => 'nullable|string|max:10',
                    'birthdate' => 'nullable|date',
                    'is_employed' => 'nullable|boolean',
                    'skills' => 'nullable|string',
                    'qualifications' => 'nullable|string',
                    'special_needs' => 'nullable|string',
                ]);

                // Handle profile photo upload
                if ($request->hasFile('profile_photo')) {
                    $request->validate([
                        'profile_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                    ]);

                    // Delete old profile photo if exists
                    if ($user->pwdProfile && $user->pwdProfile->profile_photo) {
                        Storage::disk('public')->delete($user->pwdProfile->profile_photo);
                    }

                    // Store the new profile photo
                    $pwdValidated['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
                }

                // Map disability_type_id to legacy disability_type string for compatibility
                if (!empty($pwdValidated['disability_type_id'])) {
                    $dt = DisabilityType::find($pwdValidated['disability_type_id']);
                    if ($dt) {
                        $pwdValidated['disability_type'] = $dt->type;
                    }
                }

                // Update or create PWD profile
                if ($user->pwdProfile) {
                    $user->pwdProfile->update($pwdValidated);
                } else {
                    $user->pwdProfile()->create($pwdValidated);
                }

                // Refresh the user model to get updated relationships
                $user->refresh();

                // Check if profile is now complete and update profile_completed_at
                $completionPercentage = $user->getProfileCompletionPercentage();
                if ($completionPercentage >= 80 && !$user->profile_completed_at) {
                    $user->update(['profile_completed_at' => now()]);
                }
            }

            return redirect()->route('profile.show')
                ->with('success', 'Profile updated successfully! Your profile is ' . $user->getProfileCompletionPercentage() . '% complete.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating profile: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the user's profile photo
     */
    public function deletePhoto()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            if ($user->pwdProfile && $user->pwdProfile->profile_photo) {
                Storage::disk('public')->delete($user->pwdProfile->profile_photo);
                $user->pwdProfile->update(['profile_photo' => null]);
            }

            return redirect()->route('profile.show')
                ->with('success', 'Profile photo removed successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error removing profile photo: ' . $e->getMessage());
        }
    }

    /**
     * Show the PWD profile completion form
     */
    public function showPwdCompleteForm()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if user already has a complete profile
        if ($user->hasPwdProfile() && $user->isProfileComplete()) {
            return redirect()->route('profile.show')
                ->with('info', 'Your PWD profile is already complete.');
        }

        $pwdProfile = $user->pwdProfile ?? new PwdProfile();

        $disabilityTypes = DisabilityType::orderBy('type')->get();
        $skillOptions = SkillOption::active()->orderBy('name')->get();
        $qualificationOptions = QualificationOption::active()->orderBy('name')->get();
        $accommodationOptions = AccommodationOption::active()->orderBy('name')->get();

        return view('profile.pwd-complete', [
            'user' => $user,
            'pwdProfile' => $pwdProfile,
            'disabilityTypes' => $disabilityTypes,
            'skillOptions' => $skillOptions,
            'qualificationOptions' => $qualificationOptions,
            'accommodationOptions' => $accommodationOptions,
        ]);
    }

    /**
     * Complete the PWD profile
     */
    public function completePwdProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validation rules for PWD profile
        $validated = $request->validate([
            'disability_type_id' => 'required|integer|exists:disability_types,id',
            'disability_level' => 'required|string|max:255',
            'assistive_devices' => 'nullable|string|max:500',
            'medical_conditions' => 'nullable|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:255',
            'skills' => 'nullable|string|max:1000',
            'interests' => 'nullable|string|max:1000',
            'accommodation_needs' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'pwd_id_number' => 'nullable|string|max:100',
            'pwd_id_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        try {
            // Handle file uploads
            if ($request->hasFile('profile_photo')) {
                // Delete old profile photo if exists
                if ($user->pwdProfile && $user->pwdProfile->profile_photo) {
                    Storage::disk('public')->delete($user->pwdProfile->profile_photo);
                }
                $validated['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            if ($request->hasFile('pwd_id_photo')) {
                // Delete old PWD ID photo if exists
                if ($user->pwdProfile && $user->pwdProfile->pwd_id_photo) {
                    Storage::disk('public')->delete($user->pwdProfile->pwd_id_photo);
                }
                $validated['pwd_id_photo'] = $request->file('pwd_id_photo')->store('pwd-ids', 'public');
            }

            // Map form fields to database columns
            $mappedData = [
                'disability_type_id' => $validated['disability_type_id'],
                'disability_severity' => $validated['disability_level'],
                'assistive_devices' => !empty($validated['assistive_devices']) ? json_encode(['device' => $validated['assistive_devices']]) : null,
                'special_needs' => $validated['medical_conditions'] ?? null,
                'accessibility_needs' => !empty($validated['accommodation_needs']) ? json_encode(['notes' => $validated['accommodation_needs']]) : null,
                'skills' => $validated['skills'] ?? null,
                'qualifications' => $validated['interests'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'],
                'emergency_contact_phone' => $validated['emergency_contact_phone'],
                'emergency_contact_relationship' => $validated['emergency_contact_relationship'],
                'pwd_id_number' => $validated['pwd_id_number'] ?? null,
                'profile_completed' => true,
            ];

            // Only include file paths if they were uploaded
            if (isset($validated['profile_photo'])) {
                $mappedData['profile_photo'] = $validated['profile_photo'];
            }
            if (isset($validated['pwd_id_photo'])) {
                $mappedData['pwd_id_photo'] = $validated['pwd_id_photo'];
            }

            // Also set legacy disability_type string for backward compatibility when possible
            try {
                $dt = DisabilityType::find($mappedData['disability_type_id']);
                if ($dt) {
                    $mappedData['disability_type'] = $dt->type;
                }
            } catch (\Exception $e) {
                // ignore
            }

            // Create or update PWD profile
            if ($user->pwdProfile) {
                $user->pwdProfile->update($mappedData);
            } else {
                $user->pwdProfile()->create($mappedData);
            }

            // Mark profile as complete in users table
            $user->update(['profile_completed_at' => now()]);

            return redirect()->route('profile.show')
                ->with('success', 'PWD profile completed successfully!');

        } catch (\Exception $e) {
            Log::error('Profile completion error: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Error completing profile: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Check if user has complete PWD profile
     */
    public function checkProfileComplete()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return response()->json([
            'has_pwd_profile' => $user->hasPwdProfile(),
            'is_profile_complete' => $user->isProfileComplete(),
            'profile_completed_at' => $user->profile_completed_at,
        ]);
    }

    /**
     * Upload resume
     */
    public function uploadResume(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

    if (!$user->canUploadResume()) {
        return redirect()->back()->with('error', 'You are not allowed to upload a resume.');
    }

    $request->validate([
        'resume' => 'required|file|mimes:pdf,doc,docx,txt|max:5120', // 5MB
    ]);

    try {
        $file = $request->file('resume');

        // Delete old resume if exists
        if ($user->resume) {
            Storage::disk('public')->delete($user->resume);

            // Also delete old resume document entry if exists
            \App\Models\Document::where('user_id', $user->id)
                ->where('type', 'resume')
                ->where('file_path', $user->resume)
                ->delete();
        }

        // Store new resume
        $resumePath = $file->store('resumes', 'public');
        $user->update(['resume' => $resumePath]);

        // Create document entry for tracking in Documents section
        \App\Models\Document::create([
            'user_id' => $user->id,
            'type' => 'resume',
            'name' => 'Resume - ' . $file->getClientOriginalName(),
            'file_path' => $resumePath,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'description' => 'Resume uploaded from profile',
            'is_verified' => false,
        ]);

        // Check if profile is complete
        $profileCompletion = $user->getProfileCompletionPercentage();

        if ($profileCompletion < 80 || !$user->hasCompletePwdProfile()) {
            return redirect()->route('profile.pwd-complete-form')
                ->with('success', 'Resume uploaded successfully! Please complete your profile to start applying for jobs.')
                ->with('info', 'Complete all required fields to reach 80% profile completion.');
        }

        return redirect()->route('profile.show')
            ->with('success', 'Resume uploaded successfully! You can now apply for jobs.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error uploading resume: ' . $e->getMessage());
    }
}

    /**
     * Download resume
     */
    public function downloadResume()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

    if (!$user->hasResume()) {
        return redirect()->back()->with('error', 'No resume found.');
    }

    $filePath = storage_path('app/public/' . $user->resume);

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'Resume file not found.');
    }

    return response()->download($filePath, $user->resume_file_name ?? basename($user->resume));
}

    /**
     * Delete resume
     */
    public function deleteResume()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

    try {
        if ($user->resume) {
            Storage::disk('public')->delete($user->resume);
            $user->update(['resume' => null]);
        }

        return redirect()->back()->with('success', 'Resume deleted successfully!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error deleting resume: ' . $e->getMessage());
    }
}

}
