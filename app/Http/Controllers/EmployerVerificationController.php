<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployerVerificationController extends Controller
{
    public function showApplicationForm()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isEmployer()) {
            abort(403, 'Access denied. Employer account required.');
        }

        if ($user->isEmployerPendingVerification()) {
            return redirect()->route('employer.verification.status')
                ->with('info', 'Your verification request is already pending review.');
        }

        if ($user->isEmployerVerified()) {
            return redirect()->route('employer.dashboard')
                ->with('success', 'You are already verified!');
        }

        if ($user->isEmployerRejected() && !$user->canResubmitVerification()) {
            return redirect()->route('employer.verification.status')
                ->with('error', 'You cannot resubmit verification at this time. Please check the requirements.');
        }

        return view('employer.verification.apply', [
            'user' => $user,
            'profileCompletion' => $user->getEmployerProfileCompletion()
        ]);
    }

    public function submitApplication(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isEmployer()) {
            abort(403, 'Access denied. Employer account required.');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_size' => 'required|string|in:1-10,11-50,51-200,201-500,501-1000,1000+',
            'company_type' => 'required|string|in:private,public,government,nonprofit,educational',
            'website' => 'required|url|max:255',
            'description' => 'required|string|min:100|max:2000',
            'business_registration' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'tax_clearance' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'additional_documents' => 'nullable|array',
            'additional_documents.*' => 'file|mimes:pdf,jpg,png|max:5120',
            'agree_terms' => 'required|accepted',
        ]);

        try {
            // Store documents in private storage
            $documents = [];

            // Business registration document
            if ($request->hasFile('business_registration')) {
                $file = $request->file('business_registration');
                $path = $file->store("employer-verification/{$user->id}", 'private');
                $documents['business_registration'] = $path;

                // Create document entry visible in Documents section
                \App\Models\Document::create([
                    'user_id' => $user->id,
                    'type' => 'certificate',
                    'name' => 'Business Registration - Verification',
                    'file_path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'description' => 'Business registration certificate submitted for employer verification',
                    'is_verified' => false,
                ]);
            }

            // Tax clearance document
            if ($request->hasFile('tax_clearance')) {
                $file = $request->file('tax_clearance');
                $path = $file->store("employer-verification/{$user->id}", 'private');
                $documents['tax_clearance'] = $path;

                // Create document entry visible in Documents section
                \App\Models\Document::create([
                    'user_id' => $user->id,
                    'type' => 'certificate',
                    'name' => 'Tax Clearance - Verification',
                    'file_path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'description' => 'Tax clearance certificate submitted for employer verification',
                    'is_verified' => false,
                ]);
            }

            // Additional documents
            if ($request->hasFile('additional_documents')) {
                foreach ($request->file('additional_documents') as $index => $file) {
                    $path = $file->store("employer-verification/{$user->id}", 'private');
                    $documents['additional_' . $index] = $path;

                    // Create document entry visible in Documents section
                    \App\Models\Document::create([
                        'user_id' => $user->id,
                        'type' => 'other',
                        'name' => 'Additional Document ' . ($index + 1) . ' - Verification',
                        'file_path' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                        'description' => 'Additional document submitted for employer verification',
                        'is_verified' => false,
                    ]);
                }
            }

            // Update user with verification data
            $user->update([
                'company_name' => $validated['company_name'],
                'company_size' => $validated['company_size'],
                'company_type' => $validated['company_type'],
                'website' => $validated['website'],
                'description' => $validated['description'],
                'verification_documents' => $documents,
                'employer_verification_status' => 'pending',
                'employer_verified_at' => null,
                'verification_submitted_at' => now(),
            ]);

            // TODO: Notify admin about new verification request
            // Mail::to(config('mail.admin_email'))->send(new NewEmployerVerification($user));

            return redirect()->route('employer.verification.status')
                ->with('success', 'Verification request submitted successfully! We will review your application within 2-3 business days.');

        } catch (\Exception $e) {
            Log::error('Employer verification submission failed: ' . $e->getMessage());

            return back()->with('error', 'Failed to submit verification request. Please try again.')
                ->withInput();
        }
    }

    public function status()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isEmployer()) {
            abort(403, 'Access denied. Employer account required.');
        }

        $status = $user->getEmployerVerificationStatus();
        $canResubmit = $user->isEmployerRejected() && $user->canResubmitVerification();
        $profileCompletion = $user->getEmployerProfileCompletion();

        return view('employer.verification.status', compact(
            'user', 'status', 'canResubmit', 'profileCompletion'
        ));
    }

    public function requirements()
    {
        return view('employer.verification.requirements');
    }

    public function showRenewalForm()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isEmployer() || !$user->isVerificationExpired()) {
            abort(403, 'Access denied or verification not expired.');
        }

        return view('employer.verification.renew', compact('user'));
    }

    public function submitRenewal(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isEmployer() || !$user->isVerificationExpired()) {
            abort(403, 'Access denied or verification not expired.');
        }

        $validated = $request->validate([
            'business_registration' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'tax_clearance' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'additional_documents' => 'nullable|array',
            'additional_documents.*' => 'file|mimes:pdf,jpg,png|max:5120',
            'agree_terms' => 'required|accepted',
        ]);

        try {
            // Store renewal documents
            $documents = $user->getVerificationDocuments();

            if ($request->hasFile('business_registration')) {
                $documents['business_registration_renewal'] = $request->file('business_registration')
                    ->store("employer-verification/{$user->id}/renewal", 'private');
            }

            if ($request->hasFile('tax_clearance')) {
                $documents['tax_clearance_renewal'] = $request->file('tax_clearance')
                    ->store("employer-verification/{$user->id}/renewal", 'private');
            }

            // Update user with renewal data
            $user->update([
                'verification_documents' => $documents,
                'employer_verification_status' => 'pending',
                'employer_verified_at' => null,
                'verification_submitted_at' => now(),
            ]);

            return redirect()->route('employer.verification.status')
                ->with('success', 'Renewal request submitted successfully! We will review your application within 2-3 business days.');

        } catch (\Exception $e) {
            Log::error('Employer verification renewal failed: ' . $e->getMessage());

            return back()->with('error', 'Failed to submit renewal request. Please try again.')
                ->withInput();
        }
    }
}
