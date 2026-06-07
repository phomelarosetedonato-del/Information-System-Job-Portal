<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CommunityPwdStat;

class CommunityPwdStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'year' => 2025,
                'disability_type' => 'Deaf or Hard of Hearing',
                'unemployed_count' => 67,
                'employed_count' => 34,
            ],
            [
                'year' => 2025,
                'disability_type' => 'Intellectual Disability',
                'unemployed_count' => 84,
                'employed_count' => 1,
            ],
            [
                'year' => 2025,
                'disability_type' => 'Learning Disability',
                'unemployed_count' => 14,
                'employed_count' => 1,
            ],
            [
                'year' => 2025,
                'disability_type' => 'Mental Disability',
                'unemployed_count' => 217,
                'employed_count' => 12,
            ],
            [
                'year' => 2025,
                'disability_type' => 'Physical Disability (Orthopedic)',
                'unemployed_count' => 581,
                'employed_count' => 252,
            ],
            [
                'year' => 2025,
                'disability_type' => 'Psychosocial Disability',
                'unemployed_count' => 164,
                'employed_count' => 62,
            ],
            [
                'year' => 2025,
                'disability_type' => 'Speech and Language Impairment',
                'unemployed_count' => 92,
                'employed_count' => 21,
            ],
            [
                'year' => 2025,
                'disability_type' => 'Visual Disability',
                'unemployed_count' => 118,
                'employed_count' => 71,
            ],
            [
                'year' => 2025,
                'disability_type' => 'Cancer (RA11215)',
                'unemployed_count' => 78,
                'employed_count' => 46,
            ],
            [
                'year' => 2025,
                'disability_type' => 'Rare Disease (RA 11215)',
                'unemployed_count' => 40,
                'employed_count' => 13,
            ],
        ];

        foreach ($data as $record) {
            CommunityPwdStat::updateOrCreate(
                [
                    'year' => $record['year'],
                    'disability_type' => $record['disability_type'],
                ],
                [
                    'unemployed_count' => $record['unemployed_count'],
                    'employed_count' => $record['employed_count'],
                ]
            );
        }
    }
}
