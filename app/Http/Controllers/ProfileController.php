<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PwdProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user's profile
     */
    public function show()
    {
        $user = auth()->user();
        $pwdProfile = $user->pwdProfile;

        return view('profile.show', [
            'user' => $user,
            'pwdProfile' => $pwdProfile
        ]);
    }

    /**
     * Show the form for editing the user's profile
     */
    public function edit()
    {
        $user = auth()->user();
        $pwdProfile = $user->pwdProfile; // This will be null if user doesn't have a PWD profile

        return view('profile.edit', [
            'user' => $user,
            'pwdProfile' => $pwdProfile
        ]);
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();

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
                    'disability_type' => 'required|string|max:255',
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

                // Update or create PWD profile
                if ($user->pwdProfile) {
                    $user->pwdProfile->update($pwdValidated);
                } else {
                    $user->pwdProfile()->create($pwdValidated);
                }
            }

            return redirect()->route('profile.show')
                ->with('success', 'Profile updated successfully!');

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
        $user = auth()->user();

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
        $user = auth()->user();

        // Check if user already has a complete profile
        if ($user->hasPwdProfile() && $user->isProfileComplete()) {
            return redirect()->route('profile.show')
                ->with('info', 'Your PWD profile is already complete.');
        }

        $pwdProfile = $user->pwdProfile ?? new PwdProfile();

        return view('profile.pwd-complete', [
            'user' => $user,
            'pwdProfile' => $pwdProfile
        ]);
    }

    /**
     * Complete the PWD profile
     */
    public function completePwdProfile(Request $request)
    {
        $user = auth()->user();

        // Validation rules for PWD profile
        $validated = $request->validate([
            'disability_type' => 'required|string|max:255',
            'disability_level' => 'required|string|max:255',
            'assistive_devices' => 'nullable|string|max:500',
            'medical_conditions' => 'nullable|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:255',
            'skills' => 'nullable|string|max:1000',
            'interests' => 'nullable|string|max:1000',
            'accommodation_needs' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pwd_id_number' => 'nullable|string|max:100',
            'pwd_id_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                'disability_type' => $validated['disability_type'],
                'disability_severity' => $validated['disability_level'],
                'assistive_devices' => $validated['assistive_devices'],
                'special_needs' => $validated['medical_conditions'],
                'accessibility_needs' => $validated['accommodation_needs'],
                'skills' => $validated['skills'],
                'qualifications' => $validated['interests'],
                'emergency_contact_name' => $validated['emergency_contact_name'],
                'emergency_contact_phone' => $validated['emergency_contact_phone'],
                'emergency_contact_relationship' => $validated['emergency_contact_relationship'],
                'profile_photo' => $validated['profile_photo'] ?? null,
                'pwd_id_number' => $validated['pwd_id_number'] ?? null,
                'pwd_id_photo' => $validated['pwd_id_photo'] ?? null,
                'profile_completed' => true,
            ];

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
            \Log::error('Profile completion error: ' . $e->getMessage());
            \Log::error('Mapped data: ' . print_r($mappedData, true));
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
        $user = auth()->user();

        return response()->json([
            'has_pwd_profile' => $user->hasPwdProfile(),
            'is_profile_complete' => $user->isProfileComplete(),
            'profile_completed_at' => $user->profile_completed_at,
        ]);
    }
}
