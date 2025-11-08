<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DisabilityType;

class DisabilityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Cancer',
            'Intellectual Disability',
            'Rare Disease',
            'Psychosocial Disability',
            'Speech Impairment',
            'Learning Disability',
            'Mental Disability',
            'Physical Disability',
            'Deaf/Hard of Hearing',
            'Visual Disability',
        ];

        foreach ($types as $type) {
            DisabilityType::firstOrCreate(
                ['type' => $type],
                ['name' => $type, 'is_active' => true]
            );
        }

        if ($this->command) {
            $this->command->info('Disability types seeded successfully');
        }
    }
}
