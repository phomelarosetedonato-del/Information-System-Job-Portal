<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\SkillTraining;
use App\Models\SuccessStory;
use App\Models\Employer;

class HomeController extends Controller
{
    /**
     * Display the homepage without filters
     */
    public function index()
    {
        // Initialize collections
        $featuredJobs = collect();
        $upcomingTrainings = collect();
        $successStories = collect();
        $partnerEmployers = collect();

        // Get data with error handling
        try {
            // Featured Jobs - simple query without filters
            $featuredJobs = JobPosting::where('is_active', true)
                ->where(function($query) {
                    $query->where('application_deadline', '>=', now())
                          ->orWhereNull('application_deadline');
                })
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();

        } catch (\Exception $e) {
            \Log::error('Error fetching jobs: ' . $e->getMessage());
            $featuredJobs = collect();
        }

        try {
            // Upcoming Trainings - simple query without filters
            $upcomingTrainings = SkillTraining::where('is_active', true)
                ->where('start_date', '>=', now())
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

        } catch (\Exception $e) {
            \Log::error('Error fetching trainings: ' . $e->getMessage());
            $upcomingTrainings = collect();
        }

        return view('home-public', [
            'featuredJobs' => $featuredJobs,
            'upcomingTrainings' => $upcomingTrainings,
            'successStories' => $successStories,
            'partnerEmployers' => $partnerEmployers,
        ]);
    }

    /**
     * Display about page
     */
    public function about()
    {
        $stats = [
            'jobs_posted' => 0,
            'trainings_offered' => 0,
            'partner_companies' => 0,
            'success_stories' => 0,
        ];

        // Safely get counts if models exist
        try {
            $stats['jobs_posted'] = JobPosting::where('is_active', true)->count();
        } catch (\Exception $e) {}

        try {
            $stats['trainings_offered'] = SkillTraining::where('is_active', true)->count();
        } catch (\Exception $e) {}

        try {
            $stats['partner_companies'] = Employer::where('is_partner', true)->count();
        } catch (\Exception $e) {}

        try {
            $stats['success_stories'] = SuccessStory::where('is_published', true)->count();
        } catch (\Exception $e) {}

        return view('about', compact('stats'));
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'terms' => 'required|accepted',
        ]);

        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    /**
     * Display events page
     */
    public function events()
    {
        $events = collect();

        try {
            $events = SkillTraining::where('is_active', true)
                ->where('start_date', '>=', now())
                ->orderBy('start_date')
                ->get();
        } catch (\Exception $e) {}

        return view('events', compact('events'));
    }

    /**
     * Display FAQ/Read First page
     */
    public function readFirst()
    {
        $faqs = [
            [
                'question' => 'How do I create an account?',
                'answer' => 'Click the Register button and fill in your details. You will need to provide basic information and specify if you are a job seeker or employer.'
            ],
            [
                'question' => 'Are there any fees for using this platform?',
                'answer' => 'No, our platform is completely free for PWD job seekers. We believe in providing equal opportunities without financial barriers.'
            ],
        ];

        return view('read-first', compact('faqs'));
    }

    /**
     * Display success stories page
     */
    public function successStories()
    {
        $stories = collect();

        try {
            $stories = SuccessStory::where('is_published', true)
                ->latest()
                ->paginate(6);
        } catch (\Exception $e) {}

        return view('success-stories', compact('stories'));
    }

    /**
     * Display single success story
     */
    public function showStory($id)
    {
        $story = null;
        $relatedStories = collect();

        try {
            $story = SuccessStory::where('id', $id)
                ->where('is_published', true)
                ->firstOrFail();

            $relatedStories = SuccessStory::where('id', '!=', $id)
                ->where('is_published', true)
                ->inRandomOrder()
                ->take(3)
                ->get();
        } catch (\Exception $e) {
            abort(404);
        }

        return view('story-detail', compact('story', 'relatedStories'));
    }
}
