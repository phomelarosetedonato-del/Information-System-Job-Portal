<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobPosting;
use App\Models\DisabilityType;

class JobPostingSeeder extends Seeder
{
    public function run(): void
    {
        // Create job postings
        $job1 = JobPosting::create([
            'title' => 'Data Entry Specialist - PWD Friendly',
            'description' => 'Remote data entry position suitable for various disabilities. Great opportunity for PWD individuals looking for flexible work arrangements.',
            'requirements' => 'Basic computer skills, attention to detail, reliable internet connection',
            'location' => 'Remote',
            'company' => 'Inclusive Employers Inc',
            'employment_type' => 'Full-time',
            'salary_min' => 15000,
            'salary_max' => 20000,
            'is_active' => true,
            'created_by' => 1,
        ]);
        $job1->suitableDisabilityTypes()->attach([1, 3, 5]); // Visual, Physical, Learning

        $job2 = JobPosting::create([
            'title' => 'Customer Support Representative',
            'description' => 'Phone and email support role with accommodations for hearing impairments. We provide specialized equipment and training.',
            'requirements' => 'Good communication skills, patience, customer service experience',
            'location' => 'Manila',
            'company' => 'Service Solutions Co',
            'employment_type' => 'Part-time',
            'salary_min' => 12000,
            'is_active' => true,
            'created_by' => 1,
        ]);
        $job2->suitableDisabilityTypes()->attach([2, 4]); // Hearing, Intellectual

        $job3 = JobPosting::create([
            'title' => 'Software Developer',
            'description' => 'Web development position in an inclusive workplace environment. We welcome developers from all backgrounds.',
            'requirements' => 'PHP, Laravel, JavaScript, problem-solving skills',
            'location' => 'Cebu',
            'company' => 'Tech Innovations',
            'employment_type' => 'Full-time',
            'salary_min' => 30000,
            'salary_max' => 45000,
            'is_active' => true,
            'created_by' => 1,
        ]);
        // This job has no specific disability requirements

        $job4 = JobPosting::create([
            'title' => 'Content Writer - Remote Work',
            'description' => 'Create engaging content for our website and social media. Perfect for individuals with mobility challenges.',
            'requirements' => 'Excellent writing skills, creativity, basic SEO knowledge',
            'location' => 'Remote',
            'company' => 'Digital Media Solutions',
            'employment_type' => 'Contract',
            'salary_min' => 18000,
            'salary_max' => 25000,
            'is_active' => true,
            'created_by' => 1,
        ]);
        $job4->suitableDisabilityTypes()->attach([3, 6, 7]); // Physical, Mental Health, Autism

        $this->command->info('4 sample job postings created with disability types!');
    }
}
