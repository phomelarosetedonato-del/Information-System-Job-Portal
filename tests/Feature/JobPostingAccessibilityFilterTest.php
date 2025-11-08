<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\JobPosting;
use App\Models\User;

class JobPostingAccessibilityFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_accommodations_filter_returns_only_jobs_with_accommodations()
    {
        // Create an employer user
        $user = User::factory()->create();

        // Job that provides accommodations
        $jobWith = JobPosting::create([
            'title' => 'Accessible Role',
            'description' => 'Accessible job description',
            'requirements' => 'Some requirements',
            'location' => 'City',
            'company' => 'Inclusive Co',
            'salary_min' => null,
            'salary_max' => null,
            'employment_type' => 'Full-time',
            'is_active' => true,
            'application_deadline' => null,
            'created_by' => $user->id,
            'views' => 0,
            'provides_accommodations' => true,
        ]);

        // Job that does not provide accommodations
        $jobWithout = JobPosting::create([
            'title' => 'Standard Role',
            'description' => 'Standard job description',
            'requirements' => 'Some requirements',
            'location' => 'City',
            'company' => 'Standard Co',
            'salary_min' => null,
            'salary_max' => null,
            'employment_type' => 'Full-time',
            'is_active' => true,
            'application_deadline' => null,
            'created_by' => $user->id,
            'views' => 0,
            'provides_accommodations' => false,
        ]);

    // Act as the created user and request public job listing with accommodations filter
    $this->actingAs($user);
    $response = $this->get('/jobs?accommodations=1');

        $response->assertStatus(200);
        $response->assertSeeText('Accessible Role');
        $response->assertDontSeeText('Standard Role');
    }
}
